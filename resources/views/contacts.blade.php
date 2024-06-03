<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .modal {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
    <h1 style="font-size:xx-large;">Liste des contacts</h1>

        <div class="flex justify-between mb-4">
        <form action="{{ route('contacts.index') }}" method="GET" class="flex">
    <input type="text" name="search" placeholder="Recherche" class="p-2 border border-gray-300 rounded-l-md" value="{{ request()->get('search') }}" required>
    <button type="submit" class="p-2 bg-blue-500 text-white rounded-r-md">Rechercher</button>
</form>

            @if(session('duplicate'))
    <script>
        window.onload = function() {
            openDuplicateModal();
        }
    </script>
@endif
            <a href="#" id="openAddModal" class="p-2 bg-blue-400 text-white rounded-md"><i class="fas fa-plus"></i> Ajouter</a>
        </div>
        <table class="min-w-full bg-white border border-gray-200">
        <thead>
    <tr class="bg-blue-100">
        <th class="py-2 px-4 border-b">
            <a href="{{ route('contacts.index', ['sort_column' => 'nom', 'sort_direction' => $sortColumn == 'nom' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                NOM DU CONTACT
                @if ($sortColumn == 'nom')
                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'down' : 'up' }}"></i>
                @endif
            </a>
        </th>
        <th class="py-2 px-4 border-b">
            <a href="{{ route('contacts.index', ['sort_column' => 'entreprise', 'sort_direction' => $sortColumn == 'entreprise' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                <span style="text-transform:uppercase">société</span>
                @if ($sortColumn == 'entreprise')
                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'down' : 'up' }}"></i>
                @endif
            </a>
        </th>
        <th class="py-2 px-4 border-b">
            <a href="{{ route('contacts.index', ['sort_column' => 'status', 'sort_direction' => $sortColumn == 'status' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                STATUS
                @if ($sortColumn == 'status')
                    <i class="fas fa-sort-{{ $sortDirection == 'asc' ? 'down' : 'up' }}"></i>
                @endif
            </a>
        </th>
        <th class="py-2 px-4 border-b">Actions</th>
    </tr>
</thead>

<tbody>
    @foreach ($contacts as $contact)
        <tr>
            <td class="py-2 px-4 border-b text-center">{{ $contact->nom }} {{ $contact->prenom }}</td>
            <td class="py-2 px-4 border-b text-center">{{ $contact->entreprise }}</td>
            <td class="py-2 px-4 border-b text-center">
                <span style="background-color: 
                    @if($contact->status == 'lead') blue
                    @elseif($contact->status == 'client') green
                    @elseif($contact->status == 'prospect') orange
                    @endif;
                    padding: 2px 6px;
                    border-radius: 4px;
                    color: white;
                ">{{ ucfirst($contact->status) }}</span>
            </td>
            <td class="py-2 px-4 border-b text-center">
                <a href="#" class="text-blue-500" onclick="openShowModal(event, '{{ json_encode($contact) }}')"><i class="fas fa-eye"></i></a>
                <a href="#" class="p-2 bg-blue-500 text-white rounded-md" onclick="openUpdateModal(event, '{{ route('contacts.update', $contact->id) }}', '{{ json_encode($contact) }}')"><i class="fas fa-pen"></i></a>
                <button class="text-red-500" onclick="openDeleteModal(event, '{{ route('contacts.destroy', $contact->id) }}')"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    @endforeach
</tbody>


        </table>
        <div class="mt-4">
            {{ $contacts->links() }}
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-md relative">
            <span class="modal-close absolute top-0 right-0 p-4 cursor-pointer" onclick="closeAddModal()">&times;</span>
            <h2 class="text-lg font-bold mb-4">Ajouter un Contact</h2>
            <form action="{{ route('contacts.store') }}" method="POST">
            
                @csrf
                <div class="flex mb-4">
                    <div class="w-1/2 mr-4">
                        <label for="prenom" class="block text-gray-700">Prénom:</label>
                        <input type="text" name="prenom" id="prenom" class="p-2 border border-gray-300 rounded-md w-full" required>
                    </div>
                    <div class="w-1/2">
                        <label for="nom" class="block text-gray-700">Nom:</label>
                        <input type="text" name="nom" id="nom" class="p-2 border border-gray-300 rounded-md w-full" required>
                    </div>
                </div>
                <div class="w-full mb-4">
                    <label for="email" class="block text-gray-700">E-mail:</label>
                    <input type="email" name="email" id="email" class="p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="w-full mb-4">
                    <label for="entreprise" class="block text-gray-700">Entreprise:</label>
                    <input type="text" name="entreprise" id="entreprise" class="p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="w-full mb-4">
                    <label for="adresse" class="block text-gray-700">Adresse:</label>
                    <textarea name="adresse" id="adresse" class="p-2 border border-gray-300 rounded-md w-full" required></textarea>
                </div>
                <div class="flex mb-4">
                    <div class="w-1/2">
                        <label for="code_postal" class="block text-gray-700">Code Postal:</label>
                        <input type="text" name="code_postal" id="code_postal" class="p-2 border border-gray-300 rounded-md w-full" required>
                    </div>
                    <div class="w-1/2 ml-4">
                        <label for="ville" class="block text-gray-700">Ville:</label>
                        <input type="text" name="ville" id="ville" class="p-2 border border-gray-300 rounded-md w-full" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-gray-700">Statut:</label>
                    <select name="status" id="status" class="p-2 border border-gray-300 rounded-md w-full">
                        <option value="lead">Lead</option>
                        <option value="client">Client</option>
                        <option value="prospect">Prospect</option>
                    </select>
                </div>
                <div class="flex justify-between">
                    <button type="submit" class="p-2 bg-blue-500 text-white rounded-md">Ajouter</button>
                    <a href="#" id="cancelAddModal" class="p-2 bg-red-500 text-white rounded-md" onclick="closeAddModal()">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-md relative">
            <span class="modal-close absolute top-0 right-0 p-4 cursor-pointer" onclick="closeUpdateModal()">&times;</span>
            <h2 class="text-lg font-bold mb-4">Détail du contact </h2>
            <form id="updateForm" action="#" method="POST">
                @csrf
                @method('PUT')
                <div class="flex mb-4">
                    <div class="w-1/2 mr-4">
                        <label for="update_prenom" class="block text-gray-700">Prénom:</label>
                        <input type="text" name="prenom" id="update_prenom" class="p-2 border border-gray-300 rounded-md w-full" required>
                    </div>
                    <div class="w-1/2">
                        <label for="update_nom" class="block text-gray-700">Nom:</label>
                        <input type="text" name="nom" id="update_nom" class="p-2 border border-gray-300 rounded-md w-full" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="update_email" class="block text-gray-700">E-mail:</label>
                    <input type="email" name="email" id="update_email" class="p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label for="update_entreprise" class="block text-gray-700">Société:</label>
                    <input type="text" name="entreprise" id="update_entreprise" class="p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label for="update_adresse" class="block text-gray-700">Adresse:</label>
                    <input type="text" name="adresse" id="update_adresse" class="p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="flex mb-4">
                    <div class="w-1/2 mr-4">
                        <label for="update_code_postal" class="block text-gray-700">Code_Postal:</label>
                        <input type="text" name="code_postal" id="update_code_postal" class="p-2 border border-gray-300 rounded-md w-full" required>
                    </div>
                    <div class="w-1/2">
                        <label for="update_ville" class="block text-gray-700">Ville:</label>
                        <input type="text" name="ville" id="update_ville" class="p-2 border border-gray-300 rounded-md w-full" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="update_status" class="block text-gray-700">Statut:</label>
                    <select name="status" id="update_status" class="p-2 border border-gray-300 rounded-md w-full">
                        <option value="lead">Lead</option>
                        <option value="client">Client</option>
                        <option value="prospect">Prospect</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="p-2 bg-gray-500 text-white rounded-md mr-2" onclick="closeUpdateModal()">Annuler</button>
                    <button type="submit" class="p-2 bg-blue-500 text-white rounded-md">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Show Modal -->
    <div id="showModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-md relative">
            <span class="modal-close absolute top-0 right-0 p-4 cursor-pointer" onclick="closeShowModal()">&times;</span>
            <h2 class="text-lg font-bold mb-4">Informations du Contact</h2>
            <div id="contactDetails"></div>
            <div class="flex justify-end mt-4">
                <button type="button" class="p-2 bg-red-500 text-white rounded-md" onclick="closeShowModal()">Fermer</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-md relative">
            <span class="modal-close absolute top-0 right-0 p-4 cursor-pointer" onclick="closeDeleteModal()">&times;</span>
            <h2 class="text-lg font-bold mb-4"><i class="fa-solid fa-circle-exclamation"></i> Supprimer le contact ?</h2>
            <p>Êtes-vous sûr de vouloir supprimer le contact ? <br>Cette opération est irréversible.</p>
            <form id="deleteForm" action="#" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <div class="flex justify-end">
                    <button type="button" class="p-2 bg-gray-500 text-white rounded-md mr-2" onclick="closeDeleteModal()">Annuler</button>
                    <button type="submit" class="p-2 bg-red-500 text-white rounded-md">Confirmer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('openAddModal').addEventListener('click', function() {
            document.getElementById('addModal').style.display = 'flex';
        });

        document.getElementById('cancelAddModal').addEventListener('click', function() {
            document.getElementById('addModal').style.display = 'none';
        });

        function openUpdateModal(event, actionUrl, contactData) {
            event.preventDefault();
            const contact = JSON.parse(contactData);
            document.getElementById('updateForm').action = actionUrl;
            document.getElementById('update_prenom').value = contact.prenom;
            document.getElementById('update_nom').value = contact.nom;
            document.getElementById('update_email').value = contact.email;
            document.getElementById('update_entreprise').value = contact.entreprise;
            document.getElementById('update_adresse').value = contact.adresse;
            document.getElementById('update_code_postal').value = contact.code_postal;
            document.getElementById('update_ville').value = contact.ville;
            document.getElementById('update_status').value = contact.status;
            document.getElementById('updateModal').style.display = 'flex';
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openShowModal(event, contactData) {
            event.preventDefault();
            const contact = JSON.parse(contactData);
            const detailsHtml = `
                <p><strong>Nom:</strong> ${contact.nom} ${contact.prenom}</p>
                <p><strong>E-mail:</strong> ${contact.email}</p>
                <p><strong>Entreprise:</strong> ${contact.entreprise}</p>
                <p><strong>Adresse:</strong> ${contact.adresse}</p>
                <p><strong>Code Postal:</strong> ${contact.code_postal}</p>
                <p><strong>Ville:</strong> ${contact.ville}</p>
                <p><strong>Statut:</strong> ${contact.status}</p>
            `;
            document.getElementById('contactDetails').innerHTML = detailsHtml;
            document.getElementById('showModal').style.display = 'flex';
        }

        function closeShowModal() {
            document.getElementById('showModal').style.display = 'none';
        }

        function openDeleteModal(event, actionUrl) {
            event.preventDefault();
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>
</html>

<!-- Duplicate Contact Modal -->
<div id="duplicateModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-8 rounded-md relative">
        <span class="modal-close absolute top-0 right-0 p-4 cursor-pointer" onclick="closeDuplicateModal()">&times;</span>
        <h2 class="text-lg font-bold mb-4"><i class="fa-solid fa-circle-exclamation"></i>Doublon</h2>
        <p>Un contact existe déjà avec le meme prénom et le meme nom. <br>Etes-vous sur de vouloir ajouter ce contact<br></p>
        <div class="flex justify-end mt-4">
            <button type="button" class="p-2 bg-red-500 text-white rounded-md" onclick="closeDuplicateModal()">Close</button>
        </div>
    </div>
</div>

<script>
    function openDuplicateModal() {
    document.getElementById('duplicateModal').style.display = 'flex';
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').style.display = 'none';
}
</script>
