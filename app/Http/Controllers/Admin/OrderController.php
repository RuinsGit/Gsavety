<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class OrderController extends Controller
{
    /**
     * Siparişlerin listesini göster
     */
    public function index(Request $request)
    {
        Artisan::call('migrate');
        $type = $request->input('type');
        $query = Order::with(['user', 'items']);
        
        if ($type && in_array($type, ['retail', 'corporate'])) {
            $query->where('type', $type);
        }
        
        $orders = $query->latest()->paginate(20);
        return view('back.admin.orders.index', compact('orders', 'type'));
    }

    /**
     * Perakende siparişleri göster
     */
    public function retailOrders()
    {
        $orders = Order::with(['user', 'items'])
            ->where('type', 'retail')
            ->latest()
            ->paginate(20);
        $type = 'retail';
        return view('back.admin.orders.index', compact('orders', 'type'));
    }
    
    /**
     * Kurumsal siparişleri göster
     */
    public function corporateOrders()
    {
        $orders = Order::with(['user', 'items'])
            ->where('type', 'corporate')
            ->latest()
            ->paginate(20);
        $type = 'corporate';
        return view('back.admin.orders.index', compact('orders', 'type'));
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
            'status' => 'nullable|in:pending,processing,completed,cancelled',
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
            'payment_status' => 'nullable|in:pending,paid,failed',
        ]);

        $order = Order::findOrFail($id);
        $order->payment_status = $request->payment_status;
        $order->save();

        return redirect()->back()->with('success', 'Ödeme durumu başarıyla güncellendi.');
    }
    
    /**
     * Sipariş tipini güncelle
     */
    public function updateType(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:retail,corporate',
            'company_name' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $order->type = $request->type;
        
        if ($request->type === 'corporate' && $request->has('company_name')) {
            $order->company_name = $request->company_name;
        }
        
        $order->save();

        return redirect()->back()->with('success', 'Sipariş tipi başarıyla güncellendi.');
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
    public function export(Request $request)
    {
        $type = $request->input('type');
        $query = Order::with(['items']);
        
        if ($type && in_array($type, ['retail', 'corporate'])) {
            $query->where('type', $type);
        }
        
        $orders = $query->latest()->get();
        
        $fileName = 'orders_' . ($type ? $type . '_' : '') . date('Y-m-d') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Sipariş No', 'Tarih', 'Tip', 'Şirket Adı', 'Müşteri', 'E-posta', 'Telefon', 'Toplam', 'Durum', 'Ödeme Durumu'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['Sipariş No'] = $order->order_number;
                $row['Tarih'] = $order->created_at->format('d.m.Y H:i');
                $row['Tip'] = $order->type === 'retail' ? 'Perakende' : 'Kurumsal';
                $row['Şirket Adı'] = $order->company_name ?? '-';
                $row['Müşteri'] = $order->first_name . ' ' . $order->last_name;
                $row['E-posta'] = $order->email;
                $row['Telefon'] = $order->phone;
                $row['Toplam'] = $order->total_amount;
                $row['Durum'] = $order->status;
                $row['Ödeme Durumu'] = $order->payment_status;

                fputcsv($file, array(
                    $row['Sipariş No'], 
                    $row['Tarih'], 
                    $row['Tip'],
                    $row['Şirket Adı'],
                    $row['Müşteri'], 
                    $row['E-posta'], 
                    $row['Telefon'], 
                    $row['Toplam'], 
                    $row['Durum'], 
                    $row['Ödeme Durumu']
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 