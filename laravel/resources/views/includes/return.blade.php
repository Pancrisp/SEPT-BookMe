<nav>
    <!-- hide that dashboard button on dashboard itself -->
    @if($_SERVER['REQUEST_URI'] != '/')
        <div>
            <a class="btn" href="/">Dashboard</a>
        </div>
    @endif

    <div class="right">
        <a class="btn no-pad" href="/logout">Logout</a>
    </div>
</nav>
