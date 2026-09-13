<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    // =====================================================
    // ĐĂNG KÝ
    // =====================================================

    public function showRegistrationForm()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' =>
                'Vui lòng nhập họ và tên.',

            'email.required' =>
                'Vui lòng nhập email.',

            'email.email' =>
                'Email không đúng định dạng.',

            'email.unique' =>
                'Email này đã được sử dụng.',

            'password.required' =>
                'Vui lòng nhập mật khẩu.',

            'password.min' =>
                'Mật khẩu phải có ít nhất 8 ký tự.',

            'password.confirmed' =>
                'Xác nhận mật khẩu không khớp.',
        ]);


        try {

            // =============================================
            // TẠO MÃ OTP 6 SỐ
            // =============================================

            $verificationCode =
                (string) random_int(100000, 999999);


            // =============================================
            // TẠO CUSTOMER
            // =============================================

            $user = User::create([
                'name' =>
                    $request->name,

                'email' =>
                    $request->email,

                'password' =>
                    Hash::make(
                        $request->password
                    ),

                'role' =>
                    'customer',

                'verification_code' =>
                    $verificationCode,

                'verification_code_expires_at' =>
                    now()->addMinutes(10),
            ]);


            // =============================================
            // GỬI OTP QUA EMAIL
            // =============================================

            Mail::raw(
                "Xin chào {$user->name}!\n\n"
                . "Cảm ơn bạn đã đăng ký tài khoản tại Tây Bắc Shop.\n\n"
                . "Mã xác thực email của bạn là:\n\n"
                . "{$verificationCode}\n\n"
                . "Mã này có hiệu lực trong 10 phút.\n\n"
                . "Nếu bạn không thực hiện đăng ký, vui lòng bỏ qua email này.\n\n"
                . "Tây Bắc Shop",
                function ($message) use ($user) {

                    $message
                        ->to($user->email)
                        ->subject(
                            'Mã xác thực - Tây Bắc Shop'
                        );
                }
            );


            // =============================================
            // ĐĂNG NHẬP TẠM THỜI
            // =============================================

            Auth::login($user);

            $request
                ->session()
                ->regenerate();


            // =============================================
            // CHUYỂN SANG TRANG NHẬP OTP
            // =============================================

            return redirect()
                ->route('verification.notice')
                ->with(
                    'success',
                    'Đăng ký thành công! Mã xác thực đã được gửi đến email của bạn.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Registration failed: '
                . $e->getMessage()
            );


            return back()
                ->withInput(
                    $request->except(
                        'password',
                        'password_confirmation'
                    )
                )
                ->with(
                    'error',
                    'Đăng ký thất bại. Vui lòng thử lại.'
                );
        }
    }


    // =====================================================
    // HIỂN THỊ TRANG XÁC THỰC EMAIL / OTP
    // =====================================================

   // =====================================================
// HIỂN THỊ TRANG XÁC THỰC EMAIL / OTP
// =====================================================

public function showVerifyEmail()
{
    if (!Auth::check()) {

        return redirect()
            ->route('login')
            ->with(
                'error',
                'Vui lòng đăng nhập để xác thực email.'
            );
    }

    $user = Auth::user();

    if ($user->hasVerifiedEmail()) {

        return redirect()
            ->route('welcome')
            ->with(
                'success',
                'Email của bạn đã được xác thực.'
            );
    }

    return view(
        'auth.verify-email',
        compact('user')
    );
}


// =====================================================
// XỬ LÝ MÃ OTP
// =====================================================

public function verifyEmailCode(Request $request)
{
    $request->validate([
        'verification_code' => [
            'required',
            'digits:6',
        ],
    ], [
        'verification_code.required' =>
            'Vui lòng nhập mã xác thực.',

        'verification_code.digits' =>
            'Mã xác thực phải gồm 6 chữ số.',
    ]);


    if (!Auth::check()) {

        return redirect()
            ->route('login')
            ->with(
                'error',
                'Phiên đăng nhập đã hết hạn.'
            );
    }


    $user = Auth::user();


    // Đã xác thực
    if ($user->hasVerifiedEmail()) {

        return redirect()
            ->route('welcome')
            ->with(
                'success',
                'Email đã được xác thực.'
            );
    }


    // Kiểm tra OTP
    if (
        !$user->verification_code
        ||
        (string) $user->verification_code !==
        (string) $request->verification_code
    ) {

        return back()
            ->with(
                'error',
                'Mã xác thực không chính xác.'
            );
    }


    // Kiểm tra hết hạn
    if (
        !$user->verification_code_expires_at
        ||
        Carbon::parse(
            $user->verification_code_expires_at
        )->isPast()
    ) {

        return back()
            ->with(
                'error',
                'Mã xác thực đã hết hạn. Vui lòng yêu cầu mã mới.'
            );
    }


    // Xác thực thành công
    $user->email_verified_at = now();

    $user->verification_code = null;

    $user->verification_code_expires_at = null;

    $user->save();


    return redirect()
        ->route('welcome')
        ->with(
            'success',
            'Xác thực email thành công! Chào mừng bạn đến với Tây Bắc Shop.'
        );
}


    // =====================================================
    // XỬ LÝ MÃ OTP
    // =====================================================

    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => [
                'required',
                'digits:6',
            ],
        ], [
            'verification_code.required' =>
                'Vui lòng nhập mã xác thực.',

            'verification_code.digits' =>
                'Mã xác thực phải gồm 6 chữ số.',
        ]);


        if (!Auth::check()) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Phiên đăng nhập đã hết hạn.'
                );
        }


        $user = Auth::user();


        // =============================================
        // ĐÃ XÁC THỰC
        // =============================================

        if ($user->hasVerifiedEmail()) {

            return redirect()
                ->route('welcome')
                ->with(
                    'success',
                    'Email đã được xác thực.'
                );
        }


        // =============================================
        // KIỂM TRA OTP
        // =============================================

        if (
            !$user->verification_code
            ||
            $user->verification_code !==
                $request->verification_code
        ) {

            return back()
                ->with(
                    'error',
                    'Mã xác thực không chính xác.'
                );
        }


        // =============================================
        // KIỂM TRA THỜI HẠN OTP
        // =============================================

        if (
            !$user->verification_code_expires_at
            ||
            Carbon::parse(
                $user->verification_code_expires_at
            )->isPast()
        ) {

            return back()
                ->with(
                    'error',
                    'Mã xác thực đã hết hạn. Vui lòng yêu cầu mã mới.'
                );
        }


        // =============================================
        // XÁC THỰC THÀNH CÔNG
        // =============================================

        $user->email_verified_at = now();

        $user->verification_code = null;

        $user->verification_code_expires_at = null;

        $user->save();


        return redirect()
            ->route('welcome')
            ->with(
                'success',
                'Xác thực email thành công! Chào mừng bạn đến với Tây Bắc Shop.'
            );
    }


    // =====================================================
    // GỬI LẠI MÃ OTP
    // =====================================================

    public function resendVerificationCode(Request $request)
    {
        if (!Auth::check()) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vui lòng đăng nhập trước.'
                );
        }


        $user = Auth::user();


        // Đã xác thực rồi
        if ($user->hasVerifiedEmail()) {

            return redirect()
                ->route('welcome')
                ->with(
                    'success',
                    'Email của bạn đã được xác thực.'
                );
        }


        try {

            // Tạo OTP mới
            $verificationCode =
                (string) random_int(
                    100000,
                    999999
                );


            // Cập nhật database
            $user->verification_code =
                $verificationCode;

            $user->verification_code_expires_at =
                now()->addMinutes(10);

            $user->save();


            // Gửi email
            Mail::raw(
                "Xin chào {$user->name}!\n\n"
                . "Mã xác thực mới của bạn tại Tây Bắc Shop là:\n\n"
                . "{$verificationCode}\n\n"
                . "Mã này có hiệu lực trong 10 phút.\n\n"
                . "Tây Bắc Shop",
                function ($message) use ($user) {

                    $message
                        ->to($user->email)
                        ->subject(
                            'Mã xác thực mới - Tây Bắc Shop'
                        );
                }
            );


            return back()
                ->with(
                    'success',
                    'Mã xác thực mới đã được gửi đến email của bạn.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Resend verification code failed: '
                . $e->getMessage()
            );


            return back()
                ->with(
                    'error',
                    'Không thể gửi mã xác thực. Vui lòng thử lại.'
                );
        }
    }


    // =====================================================
    // ĐĂNG NHẬP
    // =====================================================

    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        // =============================================
        // VALIDATE
        // =============================================

        $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ], [
            'email.required' =>
                'Vui lòng nhập email.',

            'email.email' =>
                'Email không đúng định dạng.',

            'password.required' =>
                'Vui lòng nhập mật khẩu.',
        ]);


        $credentials = [
            'email' =>
                $request->email,

            'password' =>
                $request->password,
        ];


        // =============================================
        // KIỂM TRA TÀI KHOẢN
        // =============================================

        if (Auth::attempt($credentials)) {

            $request
                ->session()
                ->regenerate();


            $user =
                Auth::user();


            // =========================================
            // CUSTOMER CHƯA XÁC THỰC EMAIL
            // =========================================

            if (
                $user->role !== 'admin'
                &&
                !$user->hasVerifiedEmail()
            ) {

                return redirect()
                    ->route(
                        'verification.notice'
                    )
                    ->with(
                        'error',
                        'Bạn cần xác thực email trước khi sử dụng hệ thống.'
                    );
            }


            // =========================================
            // ADMIN
            // Sau đăng nhập -> Trang chủ
            // =========================================

            if ($user->role === 'admin') {

                return redirect()
                    ->route('welcome')
                    ->with(
                        'success',
                        'Đăng nhập Admin thành công!'
                    );
            }


            // =========================================
            // CUSTOMER
            // Sau đăng nhập -> Trang chủ
            // =========================================

            return redirect()
                ->route('welcome')
                ->with(
                    'success',
                    'Đăng nhập thành công!'
                );
        }


        // =============================================
        // ĐĂNG NHẬP THẤT BẠI
        // =============================================

        return back()
            ->withErrors([
                'email' =>
                    'Email hoặc mật khẩu không chính xác.',
            ])
            ->withInput(
                $request->only('email')
            );
    }


    // =====================================================
    // ĐĂNG XUẤT
    // =====================================================

    public function logout(Request $request)
    {
        Auth::logout();


        $request
            ->session()
            ->invalidate();


        $request
            ->session()
            ->regenerateToken();


        return redirect()
            ->route('login')
            ->with(
                'success',
                'Đã đăng xuất thành công!'
            );
    }


    // =====================================================
    // PROFILE CUSTOMER
    // =====================================================

    public function profile()
    {
        $user =
            Auth::user();


        return view(
            'user.profile',
            compact('user')
        );
    }


    // =====================================================
    // PROFILE ADMIN
    // =====================================================

    public function adminProfile()
    {
        $user =
            Auth::user();


        return view(
            'admin.profile',
            compact('user')
        );
    }


    // =====================================================
    // DASHBOARD CUSTOMER
    // =====================================================

    public function dashboard()
    {
        $user =
            Auth::user();


        // Lấy toàn bộ đơn hàng của Customer
        $orders =
            $user
                ->orders()
                ->with(
                    'items.product'
                )
                ->latest()
                ->get();


        // Tổng số đơn hàng
        $totalOrders =
            $orders->count();


        // Đơn đang xử lý
        $pendingOrders =
            $orders
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'confirmed',
                    ]
                )
                ->count();


        // Đơn đã giao
        $deliveredOrders =
            $orders
                ->where(
                    'status',
                    'delivered'
                )
                ->count();


        // Tổng số tiền đã mua
        $totalSpent =
            $orders
                ->sum(
                    'total_price'
                );


        // 5 đơn gần nhất
        $recentOrders =
            $orders
                ->take(5);


        return view(
            'user.dashboard',
            compact(
                'user',
                'totalOrders',
                'pendingOrders',
                'deliveredOrders',
                'totalSpent',
                'recentOrders'
            )
        );
    }
}