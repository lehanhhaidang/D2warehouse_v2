<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
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
     *     description="Creates a new user with provided details",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Dang"),
     *             @OA\Property(property="email", type="string", example="dang@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="phone", type="string", example="0123456789"),
     *             @OA\Property(property="role_id", type="integer", example=1)
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
            // dd($request->all());
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'role_id' => $request->role_id,
            ];
            $this->userRepository->create($data);

            return response()->json([
                'message' => 'Tạo mới người dùng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
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
     *     description="Updates the details of a user based on the provided ID",
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
     *             @OA\Property(property="password", type="string", example="12345678")
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
            $user = $this->userRepository->update($id, [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'role_id' => $request->role_id,

            ]);

            if (!$user) {
                return response()->json([
                    'message' => 'Không tìm thấy người dùng này',
                    'status' => 404,
                ], 404);
            }

            return response()->json([
                'message' => 'Cập nhật người dùng thành công',
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
            ], 404);
        }

        return response()->json([
            'message' => 'Xóa người dùng thành công',
        ], 200);
    }
}
