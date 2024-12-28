import { fetchAPI } from "../utilities/fetchAPI"

// get all
export const getAllUser = async () => {
    const response = await fetchAPI({}, import.meta.env.VITE_GET_ALL);
    return response;
}

// update user

export const updateUser = async (
    id: number,
    name: string,
    email: string,
    phone: number,
    role_id: number,
    warehouse_ids: number[],
    password: string = "123456"  // Nếu không truyền mật khẩu, mặc định sẽ là "123456"
) => {
    const response = await fetchAPI({
        method: 'PATCH',
        data: {
            name,
            email,
            password,
            phone,
            role_id,
            warehouse_ids  // Cập nhật thông tin kho
        }
    }, `${import.meta.env.VITE_UPDATE_USER}/${id}`);
    
    return response;  // Trả về kết quả từ API
}


export const deleteUser = async(id:number) => {
    const response = await fetchAPI({method: 'DELETE'}, `${import.meta.env.VITE_DELETE_USER}/${id}`);
    return response;
}

export const createUser = async(name:string,email:string, password: string, role_id :number, phone:number, warehouse_ids:number[]) => {
    const response = await fetchAPI({method: 'POST', data: {name,email,password,role_id, phone,warehouse_ids}}, import.meta.env.VITE_CREATE_USER);
    return response;
}