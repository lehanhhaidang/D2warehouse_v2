import React, { useState, useEffect } from 'react';
import { Button, Input, Modal} from 'antd';
import { useLocation, useNavigate } from 'react-router-dom';
import { resetPassword } from '../service/auth.service';
import { tabTitle } from '../utilities/title';

export const ResetPassword = () => {
    const location = useLocation();
    const navigate = useNavigate();

    const [newPassword, setNewPassword] = useState('');
    const [confirmNewPassword, setConfirmNewPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [modalOpen, setModalOpen] = useState(false);
    const [modalMessage, setModalMessage] = useState('');

    // Lấy token và email từ URL
    const queryParams = new URLSearchParams(location.search);
    const token = queryParams.get('token');
    const email = queryParams.get('email');

    useEffect(() => {
        if (!token || !email) {
            setError('Token hoặc email không hợp lệ.');
        }
    }, [token, email]);

    const closeModal = () => {
        setModalOpen(false);
        navigate('/login');  // Redirect after password reset
    };

    const validatePassword = (password: string) => {
        // Regular expression for password validation
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return passwordRegex.test(password);
    };

    const handleSubmit = async (event: React.FormEvent) => {
        event.preventDefault();

        if (!token || !email) {
            setError('Token hoặc email không hợp lệ.');
            return;
        }

        if (newPassword !== confirmNewPassword) {
            setError('Mật khẩu xác nhận không khớp.');
            return;
        }

        if (!validatePassword(newPassword)) {
            setError('Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ in hoa, chữ thường, số và ký tự đặc biệt.');
            return;
        }

        try {
            setLoading(true);
            setError(null);
            const response = await resetPassword(token, email, newPassword, confirmNewPassword);
            setModalMessage(response.data.message);
            setModalOpen(true);
        } catch (err: any) {
            setError(err.response?.data?.message || 'Đã xảy ra lỗi');
        } finally {
            setLoading(false);
        }
    };

    return (
        tabTitle('D2W - Cập nhật mật khẩu'),
        <div className="flex justify-center items-center min-h-screen bg-gray-100">
            <div className="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
                <h2 className="text-2xl font-semibold text-center text-gray-800 mb-6">
                    Đổi mật khẩu
                </h2>
                <form onSubmit={handleSubmit}>
                    <div className="mb-4">
                        <Input.Password
                            placeholder="Mật khẩu mới"
                            value={newPassword}
                            onChange={(e) => setNewPassword(e.target.value)}
                            required
                            disabled={loading}
                        />
                    </div>
                    <div className="mb-4">
                        <Input.Password
                            placeholder="Xác nhận mật khẩu mới"
                            value={confirmNewPassword}
                            onChange={(e) => setConfirmNewPassword(e.target.value)}
                            required
                            disabled={loading}
                        />
                    </div>

                    {error && <p className="text-center text-sm text-red-600 mb-4">{error}</p>}

                    <Button
                        type="primary"
                        htmlType="submit"
                        block
                        disabled={loading}
                        loading={loading}
                    >
                        Đổi mật khẩu
                    </Button>
                </form>
            </div>

            {/* Ant Design Modal for success/error */}
            <Modal
                title="Thông báo"
                visible={modalOpen}
                onCancel={closeModal}
                footer={null}
                centered
            >
                <p className="pb-7">{modalMessage}</p>
                <Button type="primary" onClick={closeModal} style={{ margin: '0 auto', display: 'block' }}>
                    Đóng
                </Button>
            </Modal>
        </div>
    );
};
