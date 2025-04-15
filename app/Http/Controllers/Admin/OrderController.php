<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Siparişlerin listesini göster
     */
    public function index()
    {
        $orders = Order::with(['user', 'items'])->latest()->paginate(20);
        return view('back.admin.orders.index', compact('orders'));
    }

    /**
     * Sipariş detaylarını göster
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items', 'items.product', 'items.color', 'items.size'])->findOrFail($id);
        return view('back.admin.orders.show', compact('order'));
    }

    /**
     * Sipariş durumunu güncelle
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Sipariş durumu başarıyla güncellendi.');
    }

    /**
     * Ödeme durumunu güncelle
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order = Order::findOrFail($id);
        $order->payment_status = $request->payment_status;
        $order->save();

        return redirect()->back()->with('success', 'Ödeme durumu başarıyla güncellendi.');
    }

    /**
     * Siparişi sil
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // İlişkili kayıtları silmeden önce ilişkiyi kontrol et
        if ($order->items()->count() > 0) {
            $order->items()->delete();
        }
        
        $order->delete();
        
        return redirect()->route('back.pages.orders.index')->with('success', 'Sipariş başarıyla silindi.');
    }
    
    /**
     * Siparişleri dışa aktar (CSV)
     */
    public function export()
    {
        $orders = Order::with(['items'])->latest()->get();
        
        $fileName = 'orders_' . date('Y-m-d') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Sipariş No', 'Tarih', 'Müşteri', 'E-posta', 'Telefon', 'Toplam', 'Durum', 'Ödeme Durumu'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['Sipariş No'] = $order->order_number;
                $row['Tarih'] = $order->created_at->format('d.m.Y H:i');
                $row['Müşteri'] = $order->first_name . ' ' . $order->last_name;
                $row['E-posta'] = $order->email;
                $row['Telefon'] = $order->phone;
                $row['Toplam'] = $order->total_amount;
                $row['Durum'] = $order->status;
                $row['Ödeme Durumu'] = $order->payment_status;

                fputcsv($file, array($row['Sipariş No'], $row['Tarih'], $row['Müşteri'], $row['E-posta'], 
                                    $row['Telefon'], $row['Toplam'], $row['Durum'], $row['Ödeme Durumu']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 