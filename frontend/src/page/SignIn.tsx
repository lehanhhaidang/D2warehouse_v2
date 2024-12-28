import { useEffect, useState } from 'react';
import { Button, Checkbox, CircularProgress, FormControlLabel, TextField, Link } from '@mui/material';
import { useNavigate } from 'react-router-dom';
import * as authServices from '../service/auth.service';
import { message } from 'antd';
import { tabTitle } from '../utilities/title';

export const SignIn = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [emailError, setEmailError] = useState('');
  const [passwordError, setPasswordError] = useState('');
  const navigate = useNavigate();

  // Hàm kiểm tra định dạng email
  const validateEmail = (email: string) => {
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    // Reset errors
    setEmailError('');
    setPasswordError('');

    // Kiểm tra các trường bắt buộc
    if (!email) {
      setEmailError('Vui lòng nhập email');
      return;
    }
    if (!validateEmail(email)) {
      setEmailError('Email không hợp lệ, vui lòng nhập đúng định dạng (Ví dụ: Example@gmail.com)');
      return;
    }
    if (!password) {
      setPasswordError('Vui lòng nhập mật khẩu');
      return;
    }

    setLoading(true);

    setTimeout(async () => {
      try {
        const response = await authServices.signIn(email, password);

        if (response?.data) {
          localStorage.setItem('token', response.data.access_token);
          localStorage.setItem('user', JSON.stringify(response.data.user));
          if (response.data.user.role_id === 1) {
            navigate('/admin-index');
          } else {
            navigate('/');
          }
        } else {
          message.error('Đăng nhập thất bại');
        }
      } catch (error) {
        message.error('Tài khoản hoặc mật khẩu không chính xác');
      } finally {
        setLoading(false);
      }
    }, 2000);
  };

  useEffect(() => {
    localStorage.clear();
  }, []);

  return (
    tabTitle('D2W - Đăng nhập'),
    <section className="h-screen">
      <div className="container h-full px-6 py-24">
        <div className="g-6 flex h-full flex-wrap items-center justify-center lg:justify-between">
          <div className="mb-12 md:mb-0 md:w-8/12 lg:w-6/12">
            <img
              src="https://tecdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
              className="w-full"
              alt="Phone image"
            />
          </div>

          <div className="md:w-8/12 lg:ml-6 lg:w-5/12">
            <form noValidate onSubmit={handleSubmit}>
              <TextField
                variant="outlined"
                margin="normal"
                required
                fullWidth
                id="email"
                label="Email Address"
                name="email"
                autoComplete="email"
                autoFocus
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                error={!!emailError}
                helperText={emailError}
              />

              <TextField
                variant="outlined"
                margin="normal"
                required
                fullWidth
                name="password"
                label="Password"
                type="password"
                id="password"
                autoComplete="current-password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                error={!!passwordError}
                helperText={passwordError}
              />
              <FormControlLabel
                control={<Checkbox value="remember" color="primary" />}
                label="Remember me"
              />
              <Button
                type="submit"
                fullWidth
                variant="contained"
                color="primary"
                disabled={loading}
                startIcon={loading ? <CircularProgress size={20} color="success" /> : null}
              >
                Sign In
              </Button>
              {/* Thêm liên kết Quên mật khẩu */}
              <div className="flex justify-end mt-2 dark:text-blue-700 hover:underline">
                <Link href="/forgot-password" variant="body2">
                  Quên mật khẩu?
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  );
};
