<?php
require 'auth2.php';
requireAdmin(); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <link rel="stylesheet" href="WEBSITE.css?v=1.0">
    <style>
        .controls { margin-bottom: 20px; display: flex; gap: 10px; }
        .controls input, .controls select, .controls button { padding: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .modal { display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -20%); background: white; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); z-index: 1000; }
    </style>
</head>
<body>
    <div class="navbar" id="navbar">
    <div class="logo"><a href="mainpage.html"><img src="logo.jpg"></a></div>
    <div class="nav-links" style="margin-right: -150px;">
      <a href="admin.php">Manage Students</a>
      <a href="manage.php">Manage Events</a>
      <a href="check fees.html">Check Fees</a>
      <a href="counselling.html">Counselling</a>
      <a href="About us.html">About Us</a>
      <a href="login.html">Your Account&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
    </div>
    <div style="margin-right: 50px;">
      <button onclick="toggleTheme()" style="background-color: #000000; color: white; padding: 10px 20px; border: none; cursor: pointer;">Switch Theme</button>
    </div>
  </div>
  <div class="content">
    <div class="content box">
        <h1>Student Management Dashboard</h2><hr><br>
        
        <div class="controls">
            <input type="text" id="searchInput" placeholder="Search name or username..." onkeyup="loadStudents()">
            <select id="roleFilter" onchange="loadStudents()">
                <option value="">All Roles</option>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select>
            <button onclick="openModal()">+ Add New User</button>
        </div>

        <!-- Data Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>First Name</th><th>Last Name</th><th>Username</th><th>Role</th><th>Actions</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <!-- Rows populated by JS -->
            </tbody>
        </table>
    </div>

    <!-- Create/Update Modal Form -->
    <div id="userModal" class="modal">
        <h3 id="modalTitle">Add User</h3>
        <input type="hidden" id="userId">
        <input type="text" id="firstName" placeholder="First Name"><br><br>
        <input type="text" id="lastName" placeholder="Last Name"><br><br>
        <input type="text" id="username" placeholder="Username"><br><br>
        <select id="userRole">
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </select><br><br>
        <button onclick="saveUser()">Save</button>
        <button onclick="closeModal()">Cancel</button>
    </div>

    <script src="admin.js"></script>
    </div>
</body>
</html>