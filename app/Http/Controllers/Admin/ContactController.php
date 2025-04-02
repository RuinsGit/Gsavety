<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
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
    }

    protected function handleImageUpload($file, $prefix = '')
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // SVG dosyası kontrolü
        if ($file->getClientOriginalExtension() === 'svg') {
            $fileName = time() . '_' . $prefix . '_' . $originalFileName . '.svg';
            $file->move($this->destinationPath, $fileName);
            return 'uploads/' . $fileName;
        } else {
            // Diğer resim formatları için webp dönüşümü
            $webpFileName = time() . '_' . $prefix . '_' . $originalFileName . '.webp';

            if (!file_exists($this->destinationPath)) {
                mkdir($this->destinationPath, 0777, true);
            }

            $imageResource = imagecreatefromstring(file_get_contents($file));
            $webpPath = $this->destinationPath . '/' . $webpFileName;

            if ($imageResource) {
                imagewebp($imageResource, $webpPath, 80);
                imagedestroy($imageResource);
                return 'uploads/' . $webpFileName;
            }

            throw new \Exception('Şəkil yaradılarkən xəta baş verdi.');
        }
    }

    public function index()
    {
        $contacts = Contact::all();
        return view('back.pages.contact.index', compact('contacts'));
    }

    public function create()
    {
        if(Contact::count() > 0) {
            return redirect()->route('back.pages.contact.index')
                ->with('error', 'Maksimum 1 əlaqə məlumatı əlavə edilə bilər');
        }
        return view('back.pages.contact.create');
    }

    public function store(Request $request)
    {
        if(Contact::count() > 0) {
            return redirect()->route('back.pages.contact.index')
                ->with('error', 'Maksimum 1 əlaqə məlumatı əlavə edilə bilər');
        }
        
        $data = $request->validate([
            'number' => 'required|string|max:255',
            'number_image' => 'nullable|mimes:jpeg,png,jpg,svg,webp',
            'mail' => 'required|email|max:255',
            'mail_image' => 'nullable|mimes:jpeg,png,jpg,svg,webp',
            'address_az' => 'required|string|max:255',
            'address_en' => 'required|string|max:255',
            'address_ru' => 'required|string|max:255',
            'address_image' => 'nullable|mimes:jpeg,png,jpg,svg,webp',
            'filial_description' => 'nullable|string',
        ]);

        try {
            // Number Image
            if ($request->hasFile('number_image')) {
                $file = $request->file('number_image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return redirect()->back()->withErrors(['number_image' => 'Desteklenmeyen dosya formatı. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.']);
                }
                $data['number_image'] = $this->handleImageUpload($file, 'number');
            }

            // Mail Image
            if ($request->hasFile('mail_image')) {
                $file = $request->file('mail_image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return redirect()->back()->withErrors(['mail_image' => 'Desteklenmeyen dosya formatı. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.']);
                }
                $data['mail_image'] = $this->handleImageUpload($file, 'mail');
            }

            // Address Image
            if ($request->hasFile('address_image')) {
                $file = $request->file('address_image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return redirect()->back()->withErrors(['address_image' => 'Desteklenmeyen dosya formatı. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.']);
                }
                $data['address_image'] = $this->handleImageUpload($file, 'address');
            }

            Contact::create($data);

            return redirect()->route('back.pages.contact.index')->with('success', 'Əlaqə məlumatları uğurla əlavə edildi.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Şəkil yaradılarkən xəta baş verdi: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return view('back.pages.contact.edit', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $data = $request->validate([
            'number' => 'required|string|max:255',
            'number_image' => 'nullable|mimes:jpeg,png,jpg,svg,webp',
            'mail' => 'required|email|max:255',
            'mail_image' => 'nullable|mimes:jpeg,png,jpg,svg,webp',
            'address_az' => 'required|string|max:255',
            'address_en' => 'required|string|max:255',
            'address_ru' => 'required|string|max:255',
            'address_image' => 'nullable|mimes:jpeg,png,jpg,svg,webp',
            'filial_description' => 'nullable|string',
        ]);

        try {
            // Number Image
            if ($request->hasFile('number_image')) {
                $file = $request->file('number_image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return redirect()->back()->withErrors(['number_image' => 'Desdeklenmeyen format. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.']);
                }

                if ($contact->number_image) {
                    $oldImagePath = public_path($contact->number_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $data['number_image'] = $this->handleImageUpload($file, 'number');
            }

            // Mail Image
            if ($request->hasFile('mail_image')) {
                $file = $request->file('mail_image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return redirect()->back()->withErrors(['mail_image' => 'Desdeklenmeyen format. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.']);
                }

                if ($contact->mail_image) {
                    $oldImagePath = public_path($contact->mail_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $data['mail_image'] = $this->handleImageUpload($file, 'mail');
            }

            // Address Image
            if ($request->hasFile('address_image')) {
                $file = $request->file('address_image');
                if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                    return redirect()->back()->withErrors(['address_image' => 'Desdeklenmeyen format. Lütfen JPG, JPEG, PNG, GIF, SVG veya WEBP formatında bir dosya yükleyin.']);
                }

                if ($contact->address_image) {
                    $oldImagePath = public_path($contact->address_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $data['address_image'] = $this->handleImageUpload($file, 'address');
            }

            $contact->update($data);

            return redirect()->route('back.pages.contact.index')->with('success', 'Əlaqə məlumatları uğurla yeniləndi.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Resim işlenirken bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Delete images if they exist
        if ($contact->number_image) {
            $imagePath = public_path($contact->number_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($contact->mail_image) {
            $imagePath = public_path($contact->mail_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($contact->address_image) {
            $imagePath = public_path($contact->address_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $contact->delete();

        return redirect()->route('back.pages.contact.index')->with('success', 'Əlaqə məlumatları uğurla silindi.');
    }
} 