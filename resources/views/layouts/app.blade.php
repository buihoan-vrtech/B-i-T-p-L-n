<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tây Bắc Shop')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(
                180deg,
                #f8f9ff 0%,
                #eef2ff 100%
            );
            color: #1f2937;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: linear-gradient(
                90deg,
                #111827,
                #1f2937 35%,
                #312e81
            ) !important;

            box-shadow: 0 10px 20px rgba(17, 24, 39, 0.15);
        }

        .navbar-brand {
            letter-spacing: 0.5px;
            font-weight: 700;
            font-size: 21px;
        }

        .nav-link {
            font-weight: 500;
            border-radius: 8px;
            padding-left: 12px !important;
            padding-right: 12px !important;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .admin-link {
            color: #e0e7ff !important;
        }

        .admin-link:hover {
            color: white !important;
            background: rgba(99, 102, 241, 0.25);
        }

        .card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.12);
        }

        .btn-primary {
            background: linear-gradient(
                135deg,
                #2563eb,
                #4f46e5
            );

            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(
                135deg,
                #1d4ed8,
                #4338ca
            );
        }

        .alert {
            border: none;
            border-radius: 0.9rem;
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,.12);
        }

        .dropdown-item {
            padding: 9px 16px;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">

    <div class="container-fluid px-lg-5">

        {{-- ============================
            LOGO
        ============================ --}}
        <a
            class="navbar-brand"
            href="{{ url('/') }}"
        >
            🛍️ Tây Bắc Shop
        </a>


        {{-- MOBILE TOGGLE --}}
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarNav"
        >

            {{-- =====================================================
                CHƯA ĐĂNG NHẬP
            ===================================================== --}}
            @guest

                <ul class="navbar-nav me-auto">

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ url('/') }}"
                        >
                            🏠 Trang chủ
                        </a>

                    </li>

                </ul>


                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ route('login') }}"
                        >
                            🔐 Đăng nhập
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="btn btn-light btn-sm ms-lg-2 mt-1"
                            href="{{ route('register') }}"
                        >
                            Đăng ký
                        </a>

                    </li>

                </ul>


            @else


                {{-- =================================================
                    ADMIN
                ================================================= --}}
                @if(Auth::user()->role === 'admin')

                    <ul class="navbar-nav me-auto">

                        {{-- DASHBOARD --}}
                        <li class="nav-item">

                            <a
                                class="nav-link admin-link"
                                href="{{ route('admin.dashboard') }}"
                            >
                                📊 Dashboard
                            </a>

                        </li>


                        {{-- QUẢN LÝ SẢN PHẨM --}}
                        <li class="nav-item">

                            <a
                                class="nav-link admin-link"
                                href="{{ route('admin.products.index') }}"
                            >
                                📦 Sản phẩm
                            </a>

                        </li>


                        {{-- QUẢN LÝ DANH MỤC --}}
                        <li class="nav-item">

                            <a
                                class="nav-link admin-link"
                                href="{{ route('admin.categories.index') }}"
                            >
                                📂 Danh mục
                            </a>

                        </li>


                        {{-- QUẢN LÝ ĐƠN HÀNG --}}
                        <li class="nav-item">

                            <a
                                class="nav-link admin-link position-relative"
                                href="{{ route('admin.orders.index') }}"
                            >
                                🧾 Đơn hàng

                                @php
                                    $pendingAdminOrders =
                                        \App\Models\Order::where(
                                            'status',
                                            'pending'
                                        )->count();
                                @endphp

                                @if($pendingAdminOrders > 0)

                                    <span
                                        class="
                                            position-absolute
                                            top-0
                                            start-100
                                            translate-middle
                                            badge
                                            rounded-pill
                                            bg-danger
                                        "
                                    >
                                        {{ $pendingAdminOrders }}
                                    </span>

                                @endif

                            </a>

                        </li>

                    </ul>


                    {{-- ADMIN ACCOUNT --}}
                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item dropdown">

                            <a
                                class="nav-link dropdown-toggle"
                                href="#"
                                id="adminDropdown"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                👑 {{ Auth::user()->name }}
                            </a>


                            <ul
                                class="dropdown-menu dropdown-menu-end"
                                aria-labelledby="adminDropdown"
                            >

                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route('admin.profile') }}"
                                    >
                                        👤 Hồ sơ Admin
                                    </a>

                                </li>


                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route('admin.dashboard') }}"
                                    >
                                        📊 Dashboard
                                    </a>

                                </li>


                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route('admin.products.index') }}"
                                    >
                                        📦 Quản lý sản phẩm
                                    </a>

                                </li>


                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route('admin.categories.index') }}"
                                    >
                                        📂 Quản lý danh mục
                                    </a>

                                </li>


                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route('admin.orders.index') }}"
                                    >
                                        🧾 Quản lý đơn hàng
                                    </a>

                                </li>


                                <li>
                                    <hr class="dropdown-divider">
                                </li>


                                <li>

                                    <form
                                        action="{{ route('logout') }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="dropdown-item text-danger"
                                        >
                                            🚪 Đăng xuất
                                        </button>

                                    </form>

                                </li>

                            </ul>

                        </li>

                    </ul>


                {{-- =================================================
                    CUSTOMER
                ================================================= --}}
                @else

                    <ul class="navbar-nav me-auto">

                        {{-- TRANG CHỦ --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="{{ url('/') }}"
                            >
                                🏠 Trang chủ
                            </a>

                        </li>


                        {{-- SẢN PHẨM --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="{{ route('products.index') }}"
                            >
                                🛍️ Sản phẩm
                            </a>

                        </li>


                        {{-- DANH MỤC --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="{{ route('categories.index') }}"
                            >
                                📂 Danh mục
                            </a>

                        </li>


                        {{-- DASHBOARD USER --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="{{ route('dashboard') }}"
                            >
                                👤 Dashboard
                            </a>

                        </li>


                        {{-- ĐƠN HÀNG CỦA USER --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="{{ route('orders.index') }}"
                            >
                                📋 Đơn hàng của tôi
                            </a>

                        </li>

                    </ul>


                    {{-- CUSTOMER ACCOUNT --}}
                    <ul class="navbar-nav ms-auto align-items-lg-center">

                        {{-- GIỎ HÀNG --}}
                        <li class="nav-item me-lg-2">

                            <a
                                class="nav-link position-relative"
                                href="{{ route('cart.index') }}"
                            >
                                🛒 Giỏ hàng

                                @php
                                    $cartCount =
                                        count(session('cart', []));
                                @endphp

                                @if($cartCount > 0)

                                    <span
                                        class="
                                            position-absolute
                                            top-0
                                            start-100
                                            translate-middle
                                            badge
                                            rounded-pill
                                            bg-danger
                                        "
                                    >
                                        {{ $cartCount }}
                                    </span>

                                @endif

                            </a>

                        </li>


                        {{-- USER DROPDOWN --}}
                        <li class="nav-item dropdown">

                            <a
                                class="nav-link dropdown-toggle"
                                href="#"
                                id="customerDropdown"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                👤 Xin chào, {{ Auth::user()->name }}
                            </a>


                            <ul
                                class="dropdown-menu dropdown-menu-end"
                                aria-labelledby="customerDropdown"
                            >

                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route('profile') }}"
                                    >
                                        👤 Hồ sơ cá nhân
                                    </a>

                                </li>


                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route('orders.index') }}"
                                    >
                                        📋 Đơn hàng của tôi
                                    </a>

                                </li>


                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route('cart.index') }}"
                                    >
                                        🛒 Giỏ hàng
                                    </a>

                                </li>


                                <li>
                                    <hr class="dropdown-divider">
                                </li>


                                <li>

                                    <form
                                        action="{{ route('logout') }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="dropdown-item text-danger"
                                        >
                                            🚪 Đăng xuất
                                        </button>

                                    </form>

                                </li>

                            </ul>

                        </li>

                    </ul>

                @endif

            @endguest

        </div>

    </div>

</nav>


{{-- =====================================================
    NỘI DUNG TRANG
===================================================== --}}
<div class="container-fluid px-lg-5 pb-5">


    {{-- THÔNG BÁO THÀNH CÔNG --}}
    @if(session('success'))

        <div
            class="
                alert
                alert-success
                alert-dismissible
                fade
                show
                shadow-sm
            "
        >
            ✅ {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            >
            </button>
        </div>

    @endif


    {{-- THÔNG BÁO LỖI --}}
    @if(session('error'))

        <div
            class="
                alert
                alert-danger
                alert-dismissible
                fade
                show
                shadow-sm
            "
        >
            ❌ {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            >
            </button>
        </div>

    @endif


    {{-- VALIDATION ERROR --}}
    @if($errors->any())

        <div class="alert alert-danger shadow-sm">

            <strong>
                ❌ Có lỗi xảy ra:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @yield('content')

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
>
</script>

</body>
</html>