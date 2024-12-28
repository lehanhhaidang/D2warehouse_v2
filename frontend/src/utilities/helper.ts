export const getRoleUser = (roleID: number) => {
    return roleID === 1 ? 'Admin' : roleID === 2 ? 'Quản lý kho' : roleID === 3 ? "Giám đốc ":'Nhân viên kho' ;
}
