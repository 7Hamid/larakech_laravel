<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir le Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl mb-4">{{ $contact->nom }}</h1>
        <p><strong>Société:</strong> {{ $contact->entreprise }}</p> <!-- Updated field name -->
        <p><strong>Status:</strong> {{ $contact->status }}</p>
        <a href="{{ route('contacts.index') }}" class="p-2 bg-blue-500 text-white rounded-md">Retour</a>
    </div>
</body>
</html>
