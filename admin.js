document.addEventListener("DOMContentLoaded", loadStudents);

        async function loadStudents() {
            const search = document.getElementById('searchInput').value;
            const role = document.getElementById('roleFilter').value;
            
            const response = await fetch(`crud_api.php?action=read&search=${search}&role=${role}`);
            const result = await response.json();
            
            const tbody = document.getElementById('studentTableBody');
            tbody.innerHTML = '';
            
            if(result.status === 'success') {
                result.data.forEach(user => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.first_name}</td>
                            <td>${user.last_name}</td>
                            <td>${user.username}</td>
                            <td>${user.role}</td>
                            <td>
                                <button onclick="editUser(${user.id}, '${user.first_name}', '${user.last_name}', '${user.username}', '${user.role}')">Edit</button>
                                <button onclick="deleteUser(${user.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            }
        }

        async function saveUser() {
            const id = document.getElementById('userId').value;
            const data = {
                id: id,
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                username: document.getElementById('username').value,
                role: document.getElementById('userRole').value
            };
            
            const action = id ? 'update' : 'create';
            const response = await fetch(`crud_api.php?action=${action}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            alert(result.message);
            closeModal();
            loadStudents();
        }

        async function deleteUser(id) {
            if(confirm("Are you sure you want to delete this user?")) {
                await fetch(`crud_api.php?action=delete`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                loadStudents();
            }
        }

        function openModal() { document.getElementById('userModal').style.display = 'block'; }
        function closeModal() { 
            document.getElementById('userModal').style.display = 'none'; 
            document.getElementById('userId').value = '';
            document.getElementById('firstName').value = '';
            document.getElementById('lastName').value = '';
            document.getElementById('username').value = '';
        }
        function editUser(id, fName, lName, uname, role) {
            document.getElementById('userId').value = id;
            document.getElementById('firstName').value = fName;
            document.getElementById('lastName').value = lName;
            document.getElementById('username').value = uname;
            document.getElementById('userRole').value = role;
            document.getElementById('modalTitle').innerText = "Edit User";
            openModal();
        }