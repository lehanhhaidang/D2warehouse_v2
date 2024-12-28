import React, { useState } from 'react';
import { Button, Input, Modal} from 'antd';
import { useNavigate } from 'react-router-dom';
import { forgotPassword } from '../service/auth.service';
import { tabTitle } from '../utilities/title';

export const ForgotPassword = () => {
    const [email, setEmail] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [modalOpen, setModalOpen] = useState(false);
    const [modalMessage, setModalMessage] = useState('');
    const navigate = useNavigate();

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setError(null);
        try {
            const response = await forgotPassword(email);
            if (response.status === 200) {
                setModalMessage('Link reset mật khẩu đã được gửi đến email của bạn.');
                setModalOpen(true);
                setTimeout(() => {
                    navigate('/login');
                }, 2000);
            } else {
                setModalMessage('Có lỗi xảy ra, hãy chắc chắn bạn nhập đúng email.');
                setModalOpen(true);
            }
        } catch (err) {
            setModalMessage('Có lỗi xảy ra, hãy chắc chắn bạn nhập đúng email.');
            setModalOpen(true);
        } finally {
            setLoading(false);
        }
    };

    const closeModal = () => {
        setModalOpen(false);
    };

    return (
        tabTitle('D2W - Quên mật khẩu'),
        <div className="flex justify-center items-center min-h-screen bg-gray-100">
            <div className="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
                <h2 className="text-2xl font-semibold text-center text-gray-800 mb-6">
                    Quên mật khẩu
                </h2>
                <form onSubmit={handleSubmit}>
                    <div className="mb-4">
                        <label htmlFor="email" className="block text-sm font-medium text-gray-700 pb-2">
                            Nhập vào email đăng nhập của bạn
                        </label>
                        <Input
                            placeholder='Example@gmail.com'
                            type="email"
                            id="email"
                            name="email"
                            className="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            required
                            disabled={loading}
                        />
                    </div>

                    {error && (
                        <p className="text-center text-sm text-red-600 mb-4">{error}</p>
                    )}

                    <Button
                        type="primary"
                        htmlType="submit"
                        block
                        disabled={loading}
                        loading={loading}
                    >
                        Gửi yêu cầu
                    </Button>
                    <a href="/login" >
                        <Button
                            type="link"
                            htmlType="button"
                            block
                    >
                        Trở về trang đăng nhập
                    </Button>
                    </a>
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
