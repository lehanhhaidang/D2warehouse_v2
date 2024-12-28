import React, { useState } from 'react';
import { Button, Input, Modal } from 'antd';
import { changePassword } from '../service/auth.service'; // Import the changePassword function
import { useNavigate } from 'react-router-dom';
import { tabTitle } from '../utilities/title';

export const ChangePassword = () => {
    const [current_password, setcurrent_password] = useState('');
    const [new_password, setnew_password] = useState('');
    const [new_password_confirmation, setnew_password_confirmation] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [modalOpen, setModalOpen] = useState(false);
    const [modalMessage, setModalMessage] = useState('');
    const navigate = useNavigate();

    const closeModal = () => {
        setModalOpen(false);
        navigate('/'); // Redirect to home after password change
    };

    // Regex to validate the new password
    const validatePassword = (password: string) => {
        // Password must have at least 8 characters, 1 uppercase, 1 lowercase, 1 digit, and 1 special character
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return passwordRegex.test(password);
    };

    const handleSubmit = async (event: React.FormEvent) => {
        event.preventDefault();

        // Validation: Check if passwords match
        if (new_password !== new_password_confirmation) {
            setError('Mật khẩu xác nhận không khớp.');
            return;
        }

        // Validate new password using regex
        if (!validatePassword(new_password)) {
            setError('Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ in hoa, chữ thường, số và ký tự đặc biệt.');
            return;
        }

        try {
            setLoading(true);
            setError(null);
            const response = await changePassword(current_password, new_password, new_password_confirmation);
            setModalMessage(response.data.message || 'Mật khẩu đã được thay đổi thành công.');
            setModalOpen(true);
        } catch (err: any) {
            setError(err.response?.data?.message || 'Mật khẩu cũ không chính xác.');
        } finally {
            setLoading(false);
        }
    };

    return (
        tabTitle('D2W - Thay đổi mật khẩu'),
        <div className="flex justify-center items-center min-h-screen bg-gray-100">
            <div className="max-w-md w-full bg-white shadow-lg rounded-lg p-6 mb-40">
                <h2 className="text-2xl font-semibold text-center text-gray-800 mb-6">
                    Thay đổi mật khẩu
                </h2>
                <form onSubmit={handleSubmit}>
                    <div className="mb-4">
                        <Input.Password
                            placeholder="Mật khẩu cũ"
                            value={current_password}
                            onChange={(e) => setcurrent_password(e.target.value)}
                            required
                            disabled={loading}
                        />
                    </div>
                    <div className="mb-4">
                        <Input.Password
                            placeholder="Mật khẩu mới"
                            value={new_password}
                            onChange={(e) => setnew_password(e.target.value)}
                            required
                            disabled={loading}
                        />
                    </div>
                    <div className="mb-4">
                        <Input.Password
                            placeholder="Xác nhận mật khẩu mới"
                            value={new_password_confirmation}
                            onChange={(e) => setnew_password_confirmation(e.target.value)}
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
                        Thay đổi mật khẩu
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
                    Nhấn để trở về trang chủ
                </Button>
            </Modal>
        </div>
    );
};
