<?

namespace App\Http\Controllers\api;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function create()
    {
        return response()->json(['message' => 'Form for creating a contact message.']);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['nullable', 'max:255'],
            'email' => ['email', 'nullable', 'max:255'],
            'message' => ['required'],
        ]);

        $contact = Contact::create($attributes);

        return response()->json(['success' => 'You have submitted a message', 'contact' => $contact], 201);
    }
}
