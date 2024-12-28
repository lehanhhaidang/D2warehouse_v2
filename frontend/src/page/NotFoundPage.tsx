
import { Link } from 'react-router-dom';
import { tabTitle } from '../utilities/title';

const NotFoundPage = () => {
  return (
    tabTitle("D2W - 404"),
    <div className="flex items-center justify-center min-h-screen bg-gray-100 text-center">
      <div className="max-w-md p-8 bg-white rounded-lg shadow-lg">
        <h1 className="text-6xl font-extrabold text-blue-500">404</h1>
        <p className="mt-4 text-xl text-gray-700">
          Trang bạn tìm kiếm không tồn tại hoặc bạn không có quyền truy cập trang này.
        </p>
        <Link
          to="/"
          className="mt-6 inline-block px-6 py-2 text-lg font-semibold text-white bg-blue-500 rounded-full hover:bg-blue-600 transition-colors"
        >
          Trở về trang chủ
        </Link>
      </div>
    </div>
  );
};

export default NotFoundPage;
