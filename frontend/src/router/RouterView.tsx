import { Routes, Route, useLocation, useNavigate } from 'react-router-dom';
import { useEffect } from 'react';
import {
  SignIn,
  HomePage,
  MaterialPage,
  AdminIndex,
  AdminMaterial,
  AdminProduct,
  AdminStorage,
  AdminAccount,
  ManagerExportMaterial,
  ManagerImportMaterial,
  ManagerDetail,
  ManagerImportProduct,
  ManagerExportProduct,
  TableDetailPropose,
  InventoryReport,
  InventoryDetail,
  TableInventory,
} from '../page';
import { NavBar } from '../components';
import { ProductPage } from '../page/ProductPage';
import { AdminShelf } from '../page/AdminShelf';
import { AdminCategories } from '../page/AdminCategories';
import { ShelvesPage } from '../page/ShelvesPage';
import { ForgotPassword } from '../page/ForgotPassword';
import { ResetPassword } from '../page/ResetPassword';
import { ChangePassword } from '../page/ChangePassword';
import { ImportExportNotes } from '../page/ImportExportNotes';
import { ProposesPage } from '../page/ProposesPage';
import { WarehousePage } from '../page/WarehousesPage';
import NotFoundPage from '../page/NotFoundPage';
import { ManufacturingPlanPage } from '../page/ManufacturingPlanPage';
import { ManufacturingDetail } from '../page/ManufacturingDetail';
import { ManufacturingPlanDetail } from '../page/ManufacturingPlanDetail';
import { OrderPage } from '../page/OrderPage';
import { OrderDetail } from '../page/OrderDetail';

export const RouterView = () => {
  const token = localStorage.getItem('token');
  const location = useLocation();
  const navigate = useNavigate();
  const isLoginPage = location.pathname === '/login';
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  const roleId = user?.role_id;
  const isForgotPasswordPage = location.pathname === '/forgot-password';
  const isResetPasswordPage = location.pathname === '/reset-password';


  useEffect(() => {
    if (
      !token &&
      !['/login', '/register', '/forgot-password', '/reset-password'].includes(
        location.pathname
      )
    ) {
      navigate('/login');
    }
    if(roleId !== 1 && location.pathname.includes('/admin')) {
      navigate('/404');
    }
  }, [token,roleId, location.pathname, navigate]);

  return (
    <div>
      {!isLoginPage && !isForgotPasswordPage && !isResetPasswordPage && (
        <NavBar />
      )}
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/login" element={<SignIn />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/reset-password" element={<ResetPassword />} />
        <Route path="/change-password" element={<ChangePassword />} />
        <Route path="/material" element={<MaterialPage />} />
        <Route path="/product" element={<ProductPage />} />
        <Route path="/shelves" element={<ShelvesPage />} />
        <Route path="/notes" element={<ImportExportNotes />} />
        <Route path="/admin-index" element={<AdminIndex />} />
        <Route path="/admin-account" element={<AdminAccount />} />
        <Route path="/admin-material" element={<AdminMaterial />} />
        <Route path="/admin-product" element={<AdminProduct />} />
        <Route path="/admin-storage" element={<AdminStorage />} />
        <Route path="/admin-shelf" element={<AdminShelf />} />
        <Route path="/admin-categories" element={<AdminCategories />} />
        <Route path="/manager-import" element={<ManagerImportMaterial />} />
        <Route path="/manager-export" element={<ManagerExportMaterial />} />
        <Route path="/manager-detail/:id" element={<ManagerDetail />} />
        <Route path="/proposes" element={<ProposesPage />} />
        <Route path="/warehouses" element={<WarehousePage />} />
        <Route path="/404" element={<NotFoundPage />} />
        <Route
          path="/manager-product-import"
          element={<ManagerImportProduct />}
        />
        <Route
          path="/manager-product-export"
          element={<ManagerExportProduct />}
        />
        <Route path="/detail-propose/:id" element={<TableDetailPropose />} />
        <Route path="/inventory-reports" element={<InventoryReport />} />
        <Route path="/inventory-detail/:id" element={<InventoryDetail />} />
        <Route path="/inventory-table/:id" element={<TableInventory />} />
        <Route path="/manufacturing-plan" element={<ManufacturingPlanPage />} />
        <Route path="/manufacturing-detail/:id" element={<ManufacturingPlanDetail />} />

        <Route path="/orders" element={<OrderPage />} />
        <Route path="/order/:id" element={ <OrderDetail/>} />
      </Routes>
      {/* {!isLoginPage && <Footer />} */}
    </div>
  );
};

