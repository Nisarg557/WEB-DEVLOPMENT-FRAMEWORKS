<?php
require 'auth.php';
requireAdmin(); 
?>
<!-- Your DOCTYPE HTML starts here -->

<!DOCTYPE html>
<html>
<head>
    <title>Manage Events</title>
    <link rel="stylesheet" href="WEBSITE.css?v=1.0">
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
    <div class="box">
        <h1>Event Management Dashboard</h1>
        <hr><br>
        <div class="controls">
            <input type="text" id="searchInput" placeholder="Search events..." onkeyup="loadEvents()">
            <button onclick="openModal()">+ Add New Event</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Title</th><th>Description</th><th>Date</th><th>Actions</th>
                </tr>
            </thead>
            <tbody id="eventsTableBody">
                
            </tbody>
        </table>
    </div>

    <div id="eventModal" class="modal">
        <h3 id="modalTitle">Add Event</h3>
        <input type="hidden" id="eventId">
        <input type="text" id="eventTitle" placeholder="Event Title"><br>
        <textarea id="eventDescription" placeholder="Event Description" rows="4"></textarea><br>
        <input type="date" id="eventDate"><br>
        
        <button onclick="saveEvent()">Save</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
  </div>
  
  <!-- Added Website.js for the theme toggle -->
  <script src="Website.js" defer></script>
  <script src="manage.js" defer></script>
</body>
</html>