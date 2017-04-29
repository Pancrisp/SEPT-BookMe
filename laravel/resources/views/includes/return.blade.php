<nav>
    @if($_SERVER['REQUEST_URI'] != '/')
    <div>
        <a class="btn" href="/">Dashboard</a>
    </div>
    @endif
    <div class="right">
        <a class="btn" href="/logout">Logout</a>
    </div>
</nav>
