<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Task Manager') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --purple-primary: #6B46C1;
            --purple-dark: #553C9A;
            --purple-light: #9F7AEA;
            --purple-lighter: #E9D8FD;
            --purple-lightest: #FAF5FF;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--purple-lightest);
            padding-top: 75px;
        }

        .navbar {
            background-color: rgba(107, 70, 193, 0.3);
            transition: background 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            box-shadow: 0 10px 25px rgba(107, 70, 193, 0.1);
            z-index: 1000;
        }

        .navbar-brand {
            color: var(--purple-dark);
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            font-size: 1.25rem;
        }

        .navbar-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50px;
            padding: 0.3rem 0.5rem;
            transition: background 0.3s ease;
        }

        .navbar-profile:hover {
            background: rgba(255, 255, 255, 0.7);
        }

        .profile-info {
            text-align: right;
            line-height: 1.2;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .profile-email {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--purple-light), var(--purple-primary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .btn-primary {
            background-color: var(--purple-primary);
            border-color: var(--purple-primary);
        }

        .btn-primary:hover {
            background-color: var(--purple-dark);
            border-color: var(--purple-dark);
        }

        .nav-tabs .nav-link.active {
            color: var(--purple-primary);
            border-color: var(--purple-primary);
        }

        .form-check-input:checked {
            background-color: var(--purple-primary);
            border-color: var(--purple-primary);
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            background-color: var(--purple-dark);
            border: none;
            color: white;
            border-radius: 0.75rem;
        }

        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .nav-tabs {
                overflow-x: auto;
                flex-wrap: nowrap;
                scrollbar-width: none;
                width: 100%;
            }

            .profile-info {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand ms-2" href="#">To-Do-List</a>
                <div class="navbar-profile dropdown ms-auto">
                    <div class="d-flex align-items-center gap-3" role="button" data-bs-toggle="dropdown">
                        <div class="profile-info d-none d-md-block">
                            <div class="profile-name">{{ Auth::user()->name }}</div>
                            <div class="profile-email">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    
    <div>
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('searchTask')?.addEventListener('keyup', function(e) {
            const searchText = e.target.value.toLowerCase();
            const tasks = document.querySelectorAll('.list-group-item');
            tasks.forEach(task => {
                const title = task.querySelector('h6').textContent.toLowerCase();
                task.style.display = title.includes(searchText) ? '' : 'none';
            });
        });

        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.dataset.filter) {
                    e.preventDefault();
                    const filter = this.dataset.filter;
                    const tasks = document.querySelectorAll('.list-group-item');

                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');

                    tasks.forEach(task => {
                        const isCompleted = task.querySelector('.form-check-input').checked;
                        task.style.display = 
                            filter === 'all' || 
                            (filter === 'active' && !isCompleted) || 
                            (filter === 'completed' && isCompleted) 
                                ? '' : 'none';
                    });
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
