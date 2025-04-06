<nav class="navbar">
        <div class="navbar-logo">Tech Blogs</div>
        <ul class="navbar-links" id="navLinks">
            <li><a href="../blogs/index.php">Home</a></li>
            <li><a href="../blogs/create.php">Create Blog</a></li>
            <li><a href="#">Projects</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
        <div class="hamburger" onclick="toggleMenu()">☰</div>
</nav>

<script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>