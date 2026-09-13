@extends('layouts.app')

@section('title', 'Danh mục sản phẩm')

@section('content')

<style>
    .category-hero {
        border-radius: 26px;
        padding: 38px;
        color: white;
        overflow: hidden;
        position: relative;

        background:
            radial-gradient(
                circle at 85% 20%,
                rgba(59, 130, 246, .4),
                transparent 30%
            ),
            linear-gradient(
                135deg,
                #0f172a,
                #1e293b 50%,
                #312e81
            );

        box-shadow:
            0 15px 40px rgba(15, 23, 42, .18);
    }

    .category-hero::after {
        content: "";
        position: absolute;

        width: 300px;
        height: 300px;

        border-radius: 50%;

        background:
            rgba(255, 255, 255, .05);

        right: -100px;
        top: -120px;
    }

    .category-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        height: 100%;

        background: white;

        box-shadow:
            0 8px 25px rgba(0, 0, 0, .06);

        transition:
            transform .25s ease,
            box-shadow .25s ease;
    }

    .category-card:hover {
        transform: translateY(-6px);

        box-shadow:
            0 18px 40px rgba(37, 99, 235, .13);
    }

    .category-icon-box {
        height: 150px;

        display: flex;
        align-items: center;
        justify-content: center;

        background:
            linear-gradient(
                135deg,
                #eef2ff,
                #dbeafe
            );

        font-size: 70px;
    }

    .category-count {
        display: inline-block;

        padding:
            7px 12px;

        border-radius:
            20px;

        background:
            #eef2ff;

        color:
            #4338ca;

        font-weight:
            700;
    }

    .category-btn {
        border-radius:
            11px;

        font-weight:
            700;
    }

    .info-card {
        border: none;

        border-radius:
            18px;

        box-shadow:
            0 8px 25px rgba(0, 0, 0, .05);
    }

    .info-icon {
        width: 50px;
        height: 50px;

        border-radius:
            15px;

        display: flex;
        align-items: center;
        justify-content: center;

        background:
            #eef2ff;

        font-size:
            24px;
    }
</style>


<div class="container py-4">


    {{-- ==========================================
        HERO
    ========================================== --}}
    <section class="category-hero mb-5">

        <div
            class="row align-items-center position-relative"
            style="z-index: 2;"
        >

            <div class="col-lg-8">

                <div
                    class="fw-bold small mb-2"
                    style="
                        color:
                        rgba(255,255,255,.7);
                        letter-spacing:1px;
                    "
                >
                    ⚡ TÂY BẮC SHOP
                </div>


                <h1 class="fw-bold display-5 mb-3">

                    📂 Danh mục sản phẩm

                </h1>


                <p
                    class="lead mb-0"
                    style="
                        color:
                        rgba(255,255,255,.75);
                        max-width:700px;
                    "
                >
                    Khám phá điện thoại, laptop,
                    tai nghe, phụ kiện và các thiết bị
                    công nghệ phù hợp với nhu cầu của bạn.
                </p>

            </div>


            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">


                {{-- ADMIN --}}
                @if(
                    Auth::check()
                    &&
                    Auth::user()->role === 'admin'
                )

                    <a
                        href="{{
                            route(
                                'admin.categories.index'
                            )
                        }}"
                        class="
                            btn
                            btn-warning
                            btn-lg
                            fw-bold
                        "
                    >
                        ⚙️ Quản lý danh mục
                    </a>


                {{-- CUSTOMER --}}
                @else

                    <a
                        href="{{
                            route(
                                'products.index'
                            )
                        }}"
                        class="
                            btn
                            btn-light
                            btn-lg
                            fw-bold
                        "
                    >
                        🛍️ Xem tất cả sản phẩm
                    </a>

                @endif

            </div>

        </div>

    </section>



    {{-- ==========================================
        THỐNG KÊ
    ========================================== --}}
    <section class="mb-5">

        <div class="row g-3">

            <div class="col-md-4">

                <div class="card info-card h-100">

                    <div class="card-body p-4">

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-3
                            "
                        >

                            <div class="info-icon">
                                📂
                            </div>


                            <div>

                                <div
                                    class="
                                        text-muted
                                        small
                                    "
                                >
                                    Tổng danh mục
                                </div>


                                <h3 class="fw-bold mb-0">

                                    {{
                                        method_exists(
                                            $categories,
                                            'total'
                                        )
                                        ? $categories->total()
                                        : $categories->count()
                                    }}

                                </h3>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <div class="col-md-4">

                <div class="card info-card h-100">

                    <div class="card-body p-4">

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-3
                            "
                        >

                            <div class="info-icon">
                                💻
                            </div>


                            <div>

                                <div
                                    class="
                                        text-muted
                                        small
                                    "
                                >
                                    Thiết bị công nghệ
                                </div>


                                <h5 class="fw-bold mb-0">
                                    Đa dạng sản phẩm
                                </h5>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <div class="col-md-4">

                <div class="card info-card h-100">

                    <div class="card-body p-4">

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-3
                            "
                        >

                            <div class="info-icon">
                                🛒
                            </div>


                            <div>

                                <div
                                    class="
                                        text-muted
                                        small
                                    "
                                >
                                    Mua sắm
                                </div>


                                <h5 class="fw-bold mb-0">
                                    Nhanh chóng, tiện lợi
                                </h5>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- ==========================================
        TIÊU ĐỀ DANH SÁCH
    ========================================== --}}
    <div
        class="
            d-flex
            justify-content-between
            align-items-end
            flex-wrap
            gap-2
            mb-4
        "
    >

        <div>

            <div
                class="
                    text-primary
                    fw-bold
                    small
                    mb-1
                "
            >
                KHÁM PHÁ SẢN PHẨM
            </div>


            <h2 class="fw-bold mb-0">

                Chọn danh mục bạn quan tâm

            </h2>

        </div>


        <span
            class="
                badge
                bg-dark
                fs-6
            "
        >

            {{
                method_exists(
                    $categories,
                    'total'
                )
                ? $categories->total()
                : $categories->count()
            }}

            danh mục

        </span>

    </div>



    {{-- ==========================================
        DANH SÁCH DANH MỤC
    ========================================== --}}
    @if($categories->isEmpty())

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <div style="font-size:70px;">
                    📭
                </div>


                <h4 class="fw-bold mt-3">

                    Chưa có danh mục sản phẩm

                </h4>


                <p class="text-muted">

                    Cửa hàng hiện chưa có
                    danh mục nào để hiển thị.

                </p>


                @if(
                    Auth::check()
                    &&
                    Auth::user()->role === 'admin'
                )

                    <a
                        href="{{
                            route(
                                'admin.categories.create'
                            )
                        }}"
                        class="btn btn-primary"
                    >
                        ➕ Thêm danh mục
                    </a>

                @endif

            </div>

        </div>


    @else


        <div
            class="
                row
                row-cols-1
                row-cols-sm-2
                row-cols-lg-3
                row-cols-xl-4
                g-4
            "
        >

            @foreach($categories as $category)


                @php

                    $name =
                        mb_strtolower(
                            $category->name
                        );


                    $icon = '⚡';


                    if (
                        str_contains(
                            $name,
                            'điện thoại'
                        )
                    ) {

                        $icon = '📱';

                    }
                    elseif (
                        str_contains(
                            $name,
                            'laptop'
                        )
                    ) {

                        $icon = '💻';

                    }
                    elseif (
                        str_contains(
                            $name,
                            'tai nghe'
                        )
                    ) {

                        $icon = '🎧';

                    }
                    elseif (
                        str_contains(
                            $name,
                            'sạc'
                        )
                    ) {

                        $icon = '🔌';

                    }
                    elseif (
                        str_contains(
                            $name,
                            'chuột'
                        )
                    ) {

                        $icon = '🖱️';

                    }
                    elseif (
                        str_contains(
                            $name,
                            'bàn phím'
                        )
                    ) {

                        $icon = '⌨️';

                    }
                    elseif (
                        str_contains(
                            $name,
                            'phụ kiện'
                        )
                    ) {

                        $icon = '🎮';

                    }


                    $productCount =
                        $category
                            ->products()
                            ->count();

                @endphp



                <div class="col">

                    <div class="card category-card">


                        {{-- ICON --}}
                        <div class="category-icon-box">

                            {{ $icon }}

                        </div>



                        {{-- BODY --}}
                        <div
                            class="
                                card-body
                                p-4
                                d-flex
                                flex-column
                            "
                        >

                            <div class="mb-2">

                                <span class="category-count">

                                    {{ $productCount }}
                                    sản phẩm

                                </span>

                            </div>


                            <h4 class="fw-bold mb-2">

                                {{ $category->name }}

                            </h4>


                            <p
                                class="
                                    text-muted
                                    small
                                    flex-grow-1
                                "
                            >

                                Khám phá các sản phẩm
                                thuộc danh mục
                                {{ $category->name }}
                                tại Tây Bắc Shop.

                            </p>



                            {{-- ==================================
                                ADMIN
                            ================================== --}}
                            @if(
                                Auth::check()
                                &&
                                Auth::user()->role === 'admin'
                            )

                                <a
                                    href="{{
                                        route(
                                            'admin.categories.show',
                                            $category->id
                                        )
                                    }}"
                                    class="
                                        btn
                                        btn-outline-primary
                                        category-btn
                                        w-100
                                    "
                                >
                                    ⚙️ Xem & quản lý
                                </a>


                            {{-- ==================================
                                CUSTOMER
                            ================================== --}}
                            @else

                                <a
                                    href="{{
                                        route(
                                            'categories.show',
                                            $category->id
                                        )
                                    }}"
                                    class="
                                        btn
                                        btn-primary
                                        category-btn
                                        w-100
                                    "
                                >
                                    🛍️ Xem sản phẩm
                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


    @endif



    {{-- ==========================================
        PHÂN TRANG
    ========================================== --}}
    @if(
        method_exists(
            $categories,
            'hasPages'
        )
        &&
        $categories->hasPages()
    )

        <div
            class="
                d-flex
                justify-content-center
                mt-5
            "
        >

            {{ $categories->links() }}

        </div>

    @endif


</div>

@endsection