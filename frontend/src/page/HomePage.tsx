import { useEffect, useState } from 'react';
import * as dashboardService from '../service/dashboard.service';
import { Footer } from '../components/footer/Footer';
import { BarChart } from '@mui/x-charts/BarChart';
import { LoadingOutlined } from '@ant-design/icons';
import { tabTitle } from '../utilities/title';
export const HomePage = () => {
  const [dashboardData, setDashboardData] = useState<DashboardData | null>(null);

  useEffect(() => {
      const fetchData = async () => {
      const response = await dashboardService.getDashboardData();
      setDashboardData(response); 
    };

    fetchData();
  }, []);

  if (!dashboardData) {
    return <div className="text-center text-5xl mt-96 text-gray-600 animate-pulse"><LoadingOutlined className='text-blue-500'/></div>;
  }

  const productCategoryNames = dashboardData.productCategoryCount.map(item => item.category_name.replace('nhựa', ''));
  const productCategoryQuantities = dashboardData.productCategoryCount.map(item => parseInt(item.total_quantity));
  const materialCategoryNames = dashboardData.materialCategoryCount.map(item => item.category_name);
  const materialCategoryQuantities = dashboardData.materialCategoryCount.map(item => parseInt(item.total_quantity));  

  return (
    tabTitle('D2warehouse - Trang chủ'),
    <div className="flex w-full justify-center bg-slate-300" style={{ height: "calc(85vh)" }}>
      <div className="flex flex-col w-4/5 pt-6 pb-10 px-6 bg-slate-100 overflow-auto">

        {/* Dashboard Stats */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          {[
            { label: 'Số kho', count: dashboardData.warehouseCount, icon: '🏢', link: '/warehouses', color: 'bg-green-100', iconColor: 'text-green-600' },
            { label: 'Số kệ', count: dashboardData.shelfCount, icon: '⊞', link: '/shelves', color: 'bg-blue-100', iconColor: 'text-blue-600' },
            { label: 'Số thành phẩm', count: dashboardData.productCount, icon: '📦', link: '/product', color: 'bg-yellow-100', iconColor: 'text-yellow-600' },
            { label: 'Số nguyên vật liệu', count: dashboardData.materialCount, icon: '🧱', link: '/material', color: 'bg-orange-100', iconColor: 'text-orange-600' },
            { label: 'Số đề xuất', count: dashboardData.proposeCount, icon: '📑', link: '/proposes', color: 'bg-purple-100', iconColor: 'text-purple-600' },
            { label: 'Số phiếu kiểm kê', count: dashboardData.inventoryReportCount, icon: '📊', link: '/inventory-reports', color: 'bg-indigo-100', iconColor: 'text-indigo-600' }
          ].map((card, idx) => (
            <a key={idx} href={card.link} className="bg-white p-6 rounded-lg shadow-lg hover:transform hover:scale-105 hover:rotate-1 transition duration-300 flex items-center justify-between">
              <div>
                <h3 className="text-xl font-semibold text-gray-700">{card.label}</h3>
                <p className="text-2xl text-gray-900">{card.count}</p>
              </div>
              <div className={`${card.color} p-4 rounded-full`}>
                <span className={`text-4xl ${card.iconColor}`}>{card.icon}</span>
              </div>
            </a>
          ))}
        </div>

        {/* Charts Section */}


        {/* Receipts and Exports Section */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-3 gap-6">
            {[
              { label: 'Phiếu nhập thành phẩm', count: dashboardData.productReceiptCount, link: '/notes?filter=nhập+thành+phẩm' },
              { label: 'Phiếu xuất thành phẩm', count: dashboardData.productExportCount, link: '/notes?filter=xuất+thành+phẩm' },
              { label: 'Phiếu nhập nguyên vật liệu', count: dashboardData.materialReceiptCount, link: '/notes?filter=nhập+nguyên+vật+liệu' },
              { label: 'Phiếu xuất nguyên vật liệu', count: dashboardData.materialExportCount, link: '/notes?filter=xuất+nguyên+vật+liệu' },
              { label: 'Tổng số phiếu đã lập', count: dashboardData.totalReceptExportNote, link: '/notes' },
              { label: 'Nhân viên lập nhiều phiếu nhất', count: dashboardData.mostFrequentCreatedByName, link: '/notes' }
            ].map((item, idx) => (
              <a
                key={idx}
                href={item.link}
                className="bg-white p-4 rounded-lg shadow-lg hover:transform hover:scale-105 hover:rotate-1 transition duration-300 flex flex-col items-center justify-center"
              >
                <h3 className="text-xl font-semibold text-gray-700">{item.label}</h3>
                <p className={`text-gray-900 ${typeof item.count === 'number' ? 'text-2xl' : 'text-xl'}`}>
                  {item.count}
                </p>
              </a>
            ))}
          </div>
                <div className="flex flex-col sm:flex-row justify-between gap-6 mt-8 mb-8">
          {/* Product Category Chart */}
          <div className="w-full sm:w-2/3 bg-white p-4 rounded-lg shadow-lg">
            <h2 className="text-2xl font-semibold mb-4">Biểu đồ số lượng sản phẩm theo danh mục</h2>
            <BarChart
              
              xAxis={[{ scaleType: 'band', data: productCategoryNames }]}
              series={[{ data: productCategoryQuantities }]}
              width={600}
              height={300}
            />
          </div>

          {/* Material Category Chart */}
          <div className="w-full sm:w-2/3 bg-white p-4 rounded-lg shadow-lg">
            <h2 className="text-2xl font-semibold mb-4">Biểu đồ số lượng sản phẩm theo danh mục vật liệu</h2>
            <BarChart
              xAxis={[{ scaleType: 'band', data: materialCategoryNames }]}
              series={[{ data: materialCategoryQuantities }]}
              width={600}
              height={300}
            />
          </div>
        </div>

      </div>

      <Footer />
    </div>
  );
};
