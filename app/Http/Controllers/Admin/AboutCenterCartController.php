<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutCenterCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AboutCenterCartController extends Controller
{
    private $destinationPath;
    protected $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/svg+xml',
        'image/webp',
        'application/svg+xml',
        'application/svg',
    ];

    public function __construct()
    {
        $this->destinationPath = public_path('uploads');
        
     
        if (!File::exists($this->destinationPath)) {
            File::makeDirectory($this->destinationPath, 0777, true);
        }
    }

    /**
     * Görsel yükleme işlemini yönetir
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $prefix
     * @return string
     * @throws \Exception
     */
    protected function handleImageUpload($file, $prefix = '')
    {
        try {
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            
         
            if ($file->getClientOriginalExtension() === 'svg') {
                $fileName = time() . '_' . $prefix . '_' . $originalFileName . '.svg';
                $file->move($this->destinationPath, $fileName);
                return 'uploads/' . $fileName;
            }
            
     
            $webpFileName = time() . '_' . $prefix . '_' . $originalFileName . '.webp';
            $webpPath = $this->destinationPath . '/' . $webpFileName;
            
            $imageResource = imagecreatefromstring(file_get_contents($file));
            
            if (!$imageResource) {
                throw new \Exception('Görsel okunamadı veya desteklenmeyen bir format.');
            }
            
     
            if (!imagewebp($imageResource, $webpPath, 80)) {
                throw new \Exception('WEBP formatına dönüştürme başarısız oldu.');
            }
            
          
            imagedestroy($imageResource);
            
            return 'uploads/' . $webpFileName;
        } catch (\Exception $e) {
            Log::error('Görsel yükleme hatası: ' . $e->getMessage());
            throw new \Exception('Şəkil yaradılarkən xəta baş verdi: ' . $e->getMessage());
        }
    }

    /**
     * Tüm kayıtları listele
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $carts = AboutCenterCart::orderBy('id', 'desc')->get();
        $canCreate = $carts->count() < 1;
        return view('back.pages.about-center-cart.index', compact('carts', 'canCreate'));
    }

    /**
     * Yeni kayıt oluşturma formunu göster
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $count = AboutCenterCart::count();
        
        if ($count >= 1) {
            return redirect()->route('back.pages.about-center-cart.index')
                ->with('error', 'Yalnız bir sosial hesab bloğu əlavə edilə bilər. Mövcud bir blok var.');
        }
        
        return view('back.pages.about-center-cart.create');
    }

    /**
     * Yeni kayıt oluştur
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
       
            $validator = Validator::make($request->all(), [
                'title_az' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'title_ru' => 'required|string|max:255',
                'description_az' => 'required|string',
                'description_en' => 'required|string',
                'description_ru' => 'required|string',
                'image' => 'nullable|mimes:jpeg,png,jpg,svg,webp|max:9999',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $data = $validator->validated();


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Desteklenmeyen dosya formatı. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.'
                    ], 400);
                }
                
                $data['image'] = $this->handleImageUpload($file, 'about_center_cart');
            }

      
            AboutCenterCart::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Məlumat uğurla əlavə edildi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Kayıt oluşturma hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Xəta baş verdi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kayıt düzenleme formunu göster
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $cart = AboutCenterCart::findOrFail($id);
            return view('back.pages.about-center-cart.edit', compact('cart'));
        } catch (\Exception $e) {
            Log::error('Düzenleme sayfası gösterme hatası: ' . $e->getMessage());
            return redirect()->route('back.pages.about-center-cart.index')
                ->with('error', 'Kayıt bulunamadı: ' . $e->getMessage());
        }
    }

    /**
     * Kayıt güncelle
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $cart = AboutCenterCart::findOrFail($id);
            
          
            $validator = Validator::make($request->all(), [
                'title_az' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'title_ru' => 'required|string|max:255',
                'description_az' => 'required|string',
                'description_en' => 'required|string',
                'description_ru' => 'required|string',
                'image' => 'nullable|mimes:jpeg,png,jpg,svg,webp|max:9999',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $data = $validator->validated();

    
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Desteklenmeyen dosya formatı. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.'
                    ], 400);
                }

            
                if ($cart->image) {
                    $oldImagePath = public_path($cart->image);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }

                $data['image'] = $this->handleImageUpload($file, 'about_center_cart');
            }

          
            $cart->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Məlumat uğurla yeniləndi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Güncelleme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Xəta baş verdi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kaydı sil
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $cart = AboutCenterCart::findOrFail($id);
            
           
            if ($cart->image) {
                $imagePath = public_path($cart->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            
            $cart->delete();

            return redirect()->route('back.pages.about-center-cart.index')->with('success', 'Məlumat uğurla silindi.');
        } catch (\Exception $e) {
            Log::error('Silme hatası: ' . $e->getMessage());
            return redirect()->route('back.pages.about-center-cart.index')->with('error', 'Xəta baş verdi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Xəta baş verdi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * CKEditor için görsel yükleme
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        try {
     
            $validator = Validator::make($request->all(), [
                'upload' => 'required|mimes:jpeg,png,jpg,svg,webp|max:2048'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'error' => [
                        'message' => $validator->errors()->first('upload')
                    ]
                ], 422);
            }

            $file = $request->file('upload');
            
        
            if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                return response()->json([
                    'error' => [
                        'message' => 'Desteklenmeyen dosya formatı'
                    ]
                ], 400);
            }

    
            $imagePath = $this->handleImageUpload($file, 'editor');
            
         
            return response()->json([
                'url' => asset($imagePath)
            ]);
        } catch (\Exception $e) {
            Log::error('CKEditor görsel yükleme hatası: ' . $e->getMessage());
            return response()->json([
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }
} 

