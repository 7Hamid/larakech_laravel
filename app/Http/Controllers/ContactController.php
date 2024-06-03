<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(Request $request)
{
    $query = Contact::query();

    // Search
    $searchTerm = $request->input('search');
    if ($searchTerm) {
        $query->where(function ($query) use ($searchTerm) {
            $query->where('nom', 'like', "%$searchTerm%")
                ->orWhere('prenom', 'like', "%$searchTerm%")
                ->orWhere('entreprise', 'like', "%$searchTerm%");
        });
    }

    // Sorting
    $sortColumn = $request->input('sort_column', 'nom');
    $sortDirection = $request->input('sort_direction', 'asc');

    // Check if the sort column is valid
    $validColumns = ['nom', 'entreprise', 'status']; // Define valid sortable columns
    if (!in_array($sortColumn, $validColumns)) {
        $sortColumn = 'nom'; // Default to 'nom' if the provided column is invalid
    }

    // Apply sorting
    $query->orderBy($sortColumn, $sortDirection);

    // Pagination
    $contacts = $query->paginate(10);

    return view('contacts', compact('contacts', 'sortColumn', 'sortDirection'));
}



    public function create()
    {
        return view('create_contact');
    }

    public function store(Request $request)
{
    $existingContact = Contact::where('prenom', $request->prenom)
                                ->where('nom', $request->nom)
                                ->first();

    if ($existingContact) {
        // If contact already exists, show the duplicate modal
        return redirect()->back()->with('duplicate', true);
    }

    // Otherwise, proceed with saving the contact
    $contact = new Contact;
    $contact->prenom = $request->prenom;
    $contact->nom = $request->nom;
    $contact->email = $request->email;
    $contact->entreprise = $request->entreprise;
    $contact->adresse = $request->adresse;
    $contact->code_postal = $request->code_postal;
    $contact->ville = $request->ville;
    $contact->status = $request->status;
    $contact->save();

    return redirect()->route('contacts.index');
}

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('show_contact', compact('contact'));
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return view('edit_contact', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->prenom = $request->prenom;
        $contact->nom = $request->nom;
        $contact->email = $request->email;
        $contact->entreprise = $request->entreprise;
        $contact->adresse = $request->adresse;
        $contact->code_postal = $request->code_postal;
        $contact->ville = $request->ville;
        $contact->status = $request->status;
        $contact->save();

        return redirect()->route('contacts.index');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('contacts.index');
    }
}



