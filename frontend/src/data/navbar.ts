
import
{
  HomeOutlined,
  ProductOutlined,
  ShoppingCartOutlined,
  ImportOutlined,
  ExportOutlined,
  BarChartOutlined,
  UserOutlined,
  FileDoneOutlined,
  StockOutlined,
  TableOutlined,
  DropboxOutlined,
  FileTextOutlined,
  FileSearchOutlined,
  FileExclamationOutlined,
  ShoppingOutlined,

} from '@ant-design/icons';
import { INavBar } from '../common/interface';

export const iconMap = {
  HomeOutlined,
  ProductOutlined,
  ShoppingCartOutlined,
  ImportOutlined,
  ExportOutlined,
  BarChartOutlined,
  UserOutlined,
  FileDoneOutlined,
  StockOutlined,
};

// Default navbar items for non-admin users
export const navBar: INavBar[] = [
  {
    id: 1,
    label: 'Trang chủ',
    url: '/',
    icon: HomeOutlined,
  },
  {
    id: 2,
    label: 'Nguyên vật liệu',
    url: '/material',
    icon: DropboxOutlined,
  },
  {
    id: 3,
    label: 'Thành phẩm',
    url: '/product',
    icon: ProductOutlined,
  },
  {
    id: 4,
    label: 'Xem phiếu nhập',
    url: '/data-import',
    icon: ImportOutlined,
  },
  {
    id: 5,
    label: 'Xem phiếu xuất',
    url: '/data-export',
    icon: ExportOutlined,
  },
  {
    id: 6,
    label: 'Xem báo cáo thống kê',
    url: '/report',
    icon: BarChartOutlined,
  },
];

export const navbarManager = [ 
  {
    id: 1,
    label: 'Kho',
    url: '/warehouses',
    icon: HomeOutlined,
  },
  {
    id: 2,
    label: 'Kệ',
    url: '/shelves',
    icon: TableOutlined,
  },
  {
    id: 3,
    label: 'Nguyên vật liệu',
    url: '/material',
    icon: ProductOutlined,
  },
  {
    id: 4,
    label: 'Thành phẩm',
    url: '/product',
    icon: DropboxOutlined,
  },
    {
    id: 8,
    label: 'Kế hoạch sản xuất',
    icon: FileExclamationOutlined,
    url: '/manufacturing-plan',
  },
  {
    id: 9,
    label: 'Đơn hàng',
    icon: ShoppingCartOutlined,
    url: '/orders',
  },
  {
    id: 5,
    label: 'Đề xuất ',
    icon: FileTextOutlined,
    children: [
        {
          id: 5.1,
          label: 'Đề xuất nhập nguyên vật liệu',
          url: '/manager-import',
          icon: FileTextOutlined,
        },
        {
          id: 5.2,
          label: 'Đề xuất xuất nguyên vật liệu',
          url: '/manager-export',
          icon: FileTextOutlined,
        },
        {
          id: 5.3,
          label: 'Xét duyệt đề xuất nhập thành phẩm',
          url: '/manager-product-import',
          icon: FileTextOutlined,
        },
        {
          id: 5.4,
          label: 'Xét duyệt đề xuất xuất thành phẩm',
          url: '/manager-product-export',
          icon: FileTextOutlined,
        },
    ],
    
  },
      {
    id: 6,
    label: 'Phiếu nhập xuất',
    icon: FileDoneOutlined,
      url: '/notes',
      children: [
        {
          id: 6.1,
          label: 'Phiếu nhập thành phẩm',
          url: '/notes?filter=nhập+thành+phẩm',
          icon: FileDoneOutlined,
        },
        {
          id: 6.2,
          label: 'Phiếu xuất thành phẩm',
          url: '/notes?filter=xuất+thành+phẩm',
          icon: FileDoneOutlined,
        },
        {
          id: 6.3,
          label: 'Phiếu nhập nguyên vật liệu',
          url: '/notes?filter=nhập+nguyên+vật+liệu',
          icon: FileDoneOutlined,
        },
        {
          id: 6.4,
          label: 'Phiếu xuất nguyên vật liệu',
          url: '/notes?filter=xuất+nguyên+vật+liệu',
          icon: FileDoneOutlined,
        },
    ],
  },

  {
    id: 7,
    label: 'Báo cáo kiểm kê',
    icon: FileSearchOutlined,
    url: '/inventory-reports',
  },
  
];
export const navbarCEO = [ 
  {
    id: 1,
    label: 'Kho',
    url: '/warehouses',
    icon: HomeOutlined,
  },
  {
    id: 2,
    label: 'Kệ',
    url: '/shelves',
    icon: TableOutlined,
  },
  {
    id: 3,
    label: 'Nguyên vật liệu',
    url: '/material',
    icon: ProductOutlined,
  },
  {
    id: 4,
    label: 'Thành phẩm',
    url: '/product',
    icon: DropboxOutlined,
  },
    {
    id: 8,
    label: 'Kế hoạch sản xuất',
    icon: FileExclamationOutlined,
    url: '/manufacturing-plan',
  },
  {
    id: 9,
    label: 'Đơn hàng',
    icon: ShoppingCartOutlined,
    url: '/orders',
  },
  {
    id: 5,
    label: 'Đề xuất ',
    icon: FileTextOutlined,
    children: [
        {
          id: 5.1,
          label: 'Xét duyệt đề xuất nhập nguyên vật liệu',
          url: '/manager-import',
          icon: FileTextOutlined,
        },
        {
          id: 5.2,
          label: 'Xét duyệt đề xuất xuất nguyên vật liệu',
          url: '/manager-export',
          icon: FileTextOutlined,
        },
      ],
  },
      {
    id: 6,
    label: 'Phiếu nhập xuất',
    icon: FileDoneOutlined,
      url: '/notes',
      children: [
        {
          id: 6.1,
          label: 'Phiếu nhập thành phẩm',
          url: '/notes?filter=nhập+thành+phẩm',
          icon: FileDoneOutlined,
        },
        {
          id: 6.2,
          label: 'Phiếu xuất thành phẩm',
          url: '/notes?filter=xuất+thành+phẩm',
          icon: FileDoneOutlined,
        },
        {
          id: 6.3,
          label: 'Phiếu nhập nguyên vật liệu',
          url: '/notes?filter=nhập+nguyên+vật+liệu',
          icon: FileDoneOutlined,
        },
        {
          id: 6.4,
          label: 'Phiếu xuất nguyên vật liệu',
          url: '/notes?filter=xuất+nguyên+vật+liệu',
          icon: FileDoneOutlined,
        },
    ],
  },
        {
    id: 7,
    label: 'Báo cáo kiểm kê',
    icon: FileSearchOutlined,
    url: '/inventory-reports',
  },
 ];
export const navbarStorage = [ 
    {
    id: 1,
    label: 'Kho',
    url: '/warehouses',
    icon: HomeOutlined,
  },
  {
    id: 2,
    label: 'Kệ',
    url: '/shelves',
    icon: TableOutlined,
  },
  {
    id: 3,
    label: 'Nguyên vật liệu',
    url: '/material',
    icon: ProductOutlined,
  },
  {
    id: 4,
    label: 'Thành phẩm',
    url: '/product',
    icon: DropboxOutlined,
  },
    {
    id: 8,
    label: 'Kế hoạch sản xuất',
    icon: FileExclamationOutlined,
    url: '/manufacturing-plan',
  },
  {
    id: 9,
    label: 'Đơn hàng',
    icon: ShoppingCartOutlined,
    url: '/orders',
  },
  {
    id: 5,
    label: 'Đề xuất ',
    icon: FileTextOutlined,
    children: [
        {
          id: 5.1,
          label: 'Đề xuất nhập thành phẩm',
          url: '/manager-product-import',
          icon: FileTextOutlined,
        },
        {
          id: 5.2,
          label: 'Đề xuất xuất thành phẩm',
          url: '/manager-product-export',
          icon: FileTextOutlined,
        },

      ],
  },
      {
    id: 6,
    label: 'Phiếu nhập xuất',
    icon: FileDoneOutlined,
      url: '/notes',
      children: [
        {
          id: 6.1,
          label: 'Phiếu nhập thành phẩm',
          url: '/notes?filter=nhập+thành+phẩm',
          icon: FileDoneOutlined,
        },
        {
          id: 6.2,
          label: 'Phiếu xuất thành phẩm',
          url: '/notes?filter=xuất+thành+phẩm',
          icon: FileDoneOutlined,
        },
        {
          id: 6.3,
          label: 'Phiếu nhập nguyên vật liệu',
          url: '/notes?filter=nhập+nguyên+vật+liệu',
          icon: FileDoneOutlined,
        },
        {
          id: 6.4,
          label: 'Phiếu xuất nguyên vật liệu',
          url: '/notes?filter=xuất+nguyên+vật+liệu',
          icon: FileDoneOutlined,
        },
    ],
  },
  {
    id: 7,
    label: 'Báo cáo kiểm kê',
    icon: FileSearchOutlined,
    url: '/inventory-reports',
  },

];
export const navBarAdmin = [ 
   {
    id: 1,
    label: 'Admin Index',
    url: '/admin-index',
    icon: StockOutlined,
  },
  {
    id: 2,
    label: 'Quản lý tài khoản',
    url: '/admin-account',
    icon: UserOutlined,
  },
  {
    id: 3,
    label: 'Quản lý kho',
    url: '/admin-storage',
    icon: ProductOutlined,
  },
  {
    id: 4,
    label: 'Quản lý nguyên vật liệu',
    url: '/admin-material',
    icon: FileDoneOutlined,
  },
  {
    id: 5,
    label: 'Quản lý thành phẩm',
    url: '/admin-product',
    icon: ProductOutlined,
  },
  {
    id: 6,
    label: 'Quản lý kệ',
    url: '/admin-shelf',
    icon: BarChartOutlined,
  },
  {
    id: 7,
    label: 'Quản lý danh mục',
    url: '/admin-categories',
    icon: BarChartOutlined,
  },
];


export const getActiveKey = (pathname: string, roleId: number | undefined): string | null => {
  let navItems = navBar; 

  if (roleId === 1) navItems = navBarAdmin;
  else if (roleId === 2) navItems = navbarManager;
  else if (roleId === 3) navItems = navbarCEO;
  else if (roleId === 4) navItems = navbarStorage;

  const activeItem = navItems.find((item) => item.url === pathname);
  return activeItem ? activeItem.id.toString() : null;
};

export const getNavBarItems = (roleId: number | undefined): INavBar[] => {
  if (roleId === 1) return navBarAdmin;
  if (roleId === 2) return navbarManager;
  if (roleId === 3) return navbarCEO;
  if (roleId === 4) return navbarStorage;
  return navBar; // Default items for non-admin users
};
