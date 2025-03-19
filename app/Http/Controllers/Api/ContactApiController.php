<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
    public function index()
    {
        $contact = Contact::first();
        if (!$contact) {
            return response()->json(['message' => 'No Contacts found'], 404);
        }
        return new ContactResource($contact);
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        return new ContactResource($contact);
    }

    public function store(Request $request)
    {
        if (Contact::count() >= 1) {
            return response()->json(['error' => 'Hal-hazırda bir əlaqə mövcuddur. Yeni bir əlaqə əlavə edə bilməzsiniz.'], 400);
        }

        $data = $request->validate([
            'number' => 'required|string|max:255',
            'number_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'mail' => 'required|email|max:255',
            'mail_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'address_az' => 'required|string|max:255',
            'address_en' => 'required|string|max:255',
            'address_ru' => 'required|string|max:255',
            'address_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'filial_description' => 'nullable|string',
        ]);

        // Resimleri yükle
        $data = $this->uploadImages($request, $data);

        $contact = Contact::create($data);

        return response()->json(['message' => 'Əlaqə uğurla yaradıldı', 'data' => new ContactResource($contact)], 201);
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $data = $request->validate([
            'number' => 'required|string|max:255',
            'number_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'mail' => 'required|email|max:255',
            'mail_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'address_az' => 'required|string|max:255',
            'address_en' => 'required|string|max:255',
            'address_ru' => 'required|string|max:255',
            'address_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'filial_description' => 'nullable|string',
        ]);

        // Resimleri güncelle
        $data = $this->uploadImages($request, $data, $contact);

        $contact->update($data);

        return response()->json(['message' => 'Əlaqə uğurla yeniləndi', 'data' => new ContactResource($contact)], 200);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Resimleri sil
        $this->deleteImages($contact);

        $contact->delete();

        return response()->json(['message' => 'Əlaqə uğurla silindi'], 200);
    }

    private function uploadImages(Request $request, array $data, Contact $contact = null)
    {
        // Number Image
        if ($request->hasFile('number_image')) {
            if ($contact && $contact->number_image) {
                $this->deleteFile($contact->number_image);
            }
            $data['number_image'] = $this->saveImage($request->file('number_image'));
        }

        // Mail Image
        if ($request->hasFile('mail_image')) {
            if ($contact && $contact->mail_image) {
                $this->deleteFile($contact->mail_image);
            }
            $data['mail_image'] = $this->saveImage($request->file('mail_image'));
        }

        // Address Image
        if ($request->hasFile('address_image')) {
            if ($contact && $contact->address_image) {
                $this->deleteFile($contact->address_image);
            }
            $data['address_image'] = $this->saveImage($request->file('address_image'));
        }

        return $data;
    }

    private function saveImage($file)
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $webpFileName = time() . '_' . $originalFileName . '.webp';
        $webpPath = public_path('uploads/' . $webpFileName);

        $imageResource = imagecreatefromstring(file_get_contents($file));
        if ($imageResource) {
            imagewebp($imageResource, $webpPath, 80);
            imagedestroy($imageResource);
            return 'uploads/' . $webpFileName;
        }

        return null;
    }

    private function deleteFile($filePath)
    {
        $fullPath = public_path($filePath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    private function deleteImages(Contact $contact)
    {
        $this->deleteFile($contact->number_image);
        $this->deleteFile($contact->mail_image);
        $this->deleteFile($contact->address_image);
    }
} 