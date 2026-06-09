<nav class="app-header navbar navbar-expand bg-body shadow-sm">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Home</a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ asset('images/user.png') }}" class="user-image rounded-circle shadow"
                        alt="User Image">
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header bg-primary text-white p-3 text-center">
                        <img src="{{ asset('images/user.png') }}" class="rounded-circle shadow mb-2"
                            style="width: 80px;" alt="User Image">
                        <p>
                            {{ auth()->user()->name }}
                            <small class="d-block text-white-50">Member since
                                {{ auth()->user()->created_at->format('M. Y') }}</small>
                        </p>
                    </li>
                    <li class="user-footer d-flex justify-content-between p-3 bg-light">
                        <a href="#" class="btn btn-default btn-flat border">Profile</a>
                        <button onclick="document.getElementById('logout-form').submit()"
                            class="btn btn-default btn-flat border ms-auto">Sign out</button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
