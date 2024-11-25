<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $userRepository;
    protected $userService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserService $userService
    ) {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }


    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Get list of users",
     *     description="Returns list of users",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=2),
     *             @OA\Property(property="name", type="string", example="Dang"),
     *             @OA\Property(property="phone", type="string", example="0123456789"),
     *             @OA\Property(property="email", type="string", example="dang@gmail.com"),
     *             @OA\Property(property="img_url", type="string", nullable=true, example=null),
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-09-16T22:22:11.000000Z"),
     *             @OA\Property(property="role_name", type="string", example="Admin"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-16T22:22:12.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-16T22:22:12.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $results = $this->userRepository->all();
        return $results;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/add",
     *     summary="Add a new user",
     *     description="Creates a new user with provided details and assigns warehouses if provided",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Dang"),
     *             @OA\Property(property="email", type="string", example="dang@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="img_url", type="string", example="img.png"),
     *             @OA\Property(property="phone", type="string", example="0123456789"),
     *             @OA\Property(property="role_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="warehouse_ids",
     *                 type="array",
     *                 description="List of warehouse IDs to assign to the user",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tạo mới người dùng thành công")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra, vui lòng thử lại sau"),
     *             @OA\Property(property="error", type="string", example="Details of the error message")
     *         )
     *     )
     * )
     */



    public function store(StoreUserRequest $request)
    {
        try {
            // Chuyển data từ request sang mảng để gọi service
            $data = $request->only(['name', 'email', 'password', 'img_url', 'phone', 'role_id', 'warehouse_ids']);

            // Gọi service để tạo user mới
            $this->userService->store($data);

            return response()->json([
                'message' => 'Tạo mới người dùng thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    /**
     * @OA\Get(
     *     path="/api/v1/user/{id}",
     *     summary="Get user by ID",
     *     description="Returns user details based on the provided ID",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to retrieve",
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Dang"),
     *                 @OA\Property(property="phone", type="string", example="0123456789"),
     *                 @OA\Property(property="email", type="string", example="dang@gmail.com"),
     *                 @OA\Property(property="img_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-09-16T22:22:11.000000Z"),
     *                 @OA\Property(property="role_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-16T22:22:12.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-16T22:22:12.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy người dùng này")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng này',
            ], 404);
        }

        return response()->json([
            'user' => $user
        ], 200);
    }



    /**
     * @OA\Put(
     *     path="/api/v1/user/update/{id}",
     *     summary="Update user by ID",
     *     description="Updates the details of a user and assigns warehouses if provided",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to update",
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Dang"),
     *             @OA\Property(property="email", type="string", example="dang@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="img_url", type="string", example="img.png"),
     *             @OA\Property(property="phone", type="string", example="0123456789"),
     *             @OA\Property(property="role_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="warehouse_ids",
     *                 type="array",
     *                 description="List of warehouse IDs to assign to the user",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật người dùng thành công")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy người dùng này")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra, vui lòng thử lại sau"),
     *             @OA\Property(property="error", type="string", example="Details of the error message")
     *         )
     *     )
     * )
     */

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            // Chuyển data từ request sang mảng để gọi service
            $data = $request->only(['name', 'email', 'password', 'img_url', 'phone', 'role_id', 'warehouse_ids']);

            // Gọi service để cập nhật người dùng
            $this->userService->update($data, $id);

            return response()->json([
                'message' => 'Cập nhật người dùng thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
                'error' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user/delete/{id}",
     *     summary="Delete user by ID",
     *     description="Deletes a user based on the provided ID",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to delete",
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa người dùng thành công")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy người dùng này")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $deleted = $this->userRepository->delete($id);
        if (!$deleted) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng này',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'message' => 'Xóa người dùng thành công',
            'status' => 200,
        ], 200);
    }


    public function changePassword(Request $request)
    {
        // Xác thực dữ liệu yêu cầu
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed', // Đảm bảo mật khẩu mới có xác nhận
        ]);

        $user = User::find(Auth::id()); // Lấy người dùng hiện tại

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Mật khẩu cũ không chính xác.'],
            ]);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);

        $user->save();

        return response()->json([
            'message' => 'Mật khẩu đã được thay đổi thành công.',
        ]);
    }


    public function sendResetLink(Request $request)
    {
        // Validate email
        $request->validate(['email' => 'required|email']);

        // Tìm người dùng với email đã cho
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email bạn vừa nhập không tồn tại'], 404);
        }

        // Tạo token mới cho reset mật khẩu
        $token = Str::random(60);

        // Lưu token vào bảng password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Tạo URL để reset mật khẩu
        $resetUrl = env("VITE_BASE_URL") . '/reset-password/' . '?token=' . $token . '?email=' . urlencode($request->email);

        // Gửi email với link reset mật khẩu
        Mail::to($request->email)->send(new ResetPasswordMail($token, $resetUrl));

        return response()->json([
            'message' => 'Link reset mật khẩu đã được gửi đến email của bạn',
        ]);
    }

    // Reset mật khẩu
    public function resetPassword(Request $request)
    {
        // Validate request
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed', // Kiểm tra mật khẩu với xác nhận
        ]);

        // Tìm token trong bảng password_reset_tokens
        $passwordReset = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Token không hợp lệ'], 404);
        }

        // Kiểm tra thời gian hết hạn của token (ví dụ: 30 giây cho ví dụ này)
        $tokenCreatedAt = \Carbon\Carbon::parse($passwordReset->created_at);
        $expiryTime = $tokenCreatedAt->addHours(1);

        if (\Carbon\Carbon::now()->greaterThan($expiryTime)) {
            return response()->json(['message' => 'Token reset mật khẩu đã hết hạn'], 400);
        }

        // Kiểm tra xem email trong token có trùng với email trong request không
        if ($passwordReset->email !== $request->email) {
            return response()->json(['message' => 'Email không khớp với token'], 400);
        }

        // Tiến hành reset mật khẩu nếu token còn hiệu lực
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Cập nhật mật khẩu mới
            $user->password = bcrypt($request->password);
            $user->save();

            // Xóa token sau khi sử dụng
            DB::table('password_reset_tokens')->where('token', $request->token)->delete();

            return response()->json(['message' => 'Mật khẩu đã được thay đổi thành công']);
        }

        return response()->json(['message' => 'Không tìm thấy người dùng với email này'], 404);
    }

    /**
     * @OA\Get(
     *     path="/v1/users/warehouse-employees/{id}",
     *     summary="Lấy danh sách nhân viên trong một kho",
     *     description="Trả về danh sách các nhân viên thuộc một kho cụ thể dựa trên ID kho.",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của kho cần lấy danh sách nhân viên",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách nhân viên trong kho",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=4),
     *                 @OA\Property(property="name", type="string", example="Nguyễn Huỳnh Hương")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy kho hoặc kho không có nhân viên."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    public function getemployeeByWarehouse($id)
    {
        $results = $this->userRepository->getEmployeeByWarehouse($id);
        return $results;
    }
}
