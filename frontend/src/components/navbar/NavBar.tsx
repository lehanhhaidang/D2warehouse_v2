import { Avatar, Button, Drawer, Dropdown, Input, MenuProps, Space } from 'antd';
import { useEffect, useState, useRef } from 'react';
import { LockOutlined, LogoutOutlined, MenuOutlined, UserOutlined } from '@ant-design/icons';
import './style.css';
import * as dataNavBar from '../../data/navbar';
import { IUser } from '../../common/interface';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import * as helper from '../../utilities/helper';
import logo from '../../assets/logo1.png';
import NotificationDropdown from './NotificationDropdown';
import NavBarMenu from './NavBarMenu'; // Import the new component

export const NavBar = () => {
  const { Search } = Input;
  type SearchProps = Parameters<typeof Input.Search>[0]; // Corrected type for search props
  const [open, setOpen] = useState(false);
  const [activeKey, setActiveKey] = useState<string | null>(null);
  const drawerRef = useRef<HTMLDivElement | null>(null);
  const navigate = useNavigate();
  const location = useLocation();
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');

  const items: MenuProps['items'] = [
    {
      label: (
        <p className="text-center text-gray-400">
          {helper.getRoleUser(userInfor?.role_id)}
        </p>
      ),
      key: '0',
    },
    {
      label: <p className="font-bold text-base">{userInfor?.name}</p>,
      key: '1',
    },
    {
      type: 'divider',
    },
    {
      label: (
        <div className="flex justify-center items-center space-x-4">
          <Link to="/change-password">
            <Button 
              type="default" 
              className="w-44 rounded-lg py-2 px-4 text-sm font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-transform transform hover:scale-105"
              icon={<LockOutlined />} 
            >
              Đổi mật khẩu
            </Button>
          </Link>
        </div>
      ),
      key: '3',
    },
    {
      label: (
        <div className="flex justify-center items-center space-x-4">
          <Link to="/login">
            <Button 
              type="primary" 
              className="w-44 rounded-lg py-2 px-4 text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-transform transform hover:scale-105"
              icon={<LogoutOutlined />} 
            >
              Đăng xuất
            </Button>
          </Link>
        </div>
      ),
      key: '4',
    },
  ];

  const showDrawer = () => setOpen(true);
  const onClose = () => setOpen(false);

  useEffect(() => {
    const currentKey = dataNavBar.getActiveKey(location.pathname, userInfor?.role_id);
    setActiveKey(currentKey);

    if (drawerRef.current) {
      const activeElement = drawerRef.current.querySelector(
        `div[data-key="${currentKey}"]`
      );
      if (activeElement) {
        activeElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [location.pathname]);

  return (
    <div className="w-full flex flex-row justify-between py-4 navbar-background">
      <div className="w-1/6 flex flex-row justify-evenly items-center">
        <Button onClick={showDrawer}>
          <MenuOutlined />
        </Button>
        <Link to={userInfor?.role_id === 1 ? '/admin-index' : '/'}>
          <img
            src={logo}
            alt="logo"
            style={{ width: '100px', height: '90px', marginTop: '-25px', marginBottom: '-25px' }}
          />
        </Link>
      </div>
      <div className="w-1/3">
        <Search placeholder="search"  />
      </div>
      <div className="w-1/6 flex flex-row justify-evenly items-center mr-2">
        <NotificationDropdown />
        <Dropdown menu={{ items }} trigger={['click']}>
          <Space>
            <Avatar className="cursor-pointer">
              <UserOutlined />
            </Avatar>
            <p className="cursor-pointer text-white">{userInfor?.name}</p>
          </Space>
        </Dropdown>
      </div>

      <Drawer title="D2WAREHOUSE" onClose={onClose} open={open} placement="left">
        <NavBarMenu 
          roleId={userInfor?.role_id} 
          activeKey={activeKey} 
          onClose={onClose} 
        />
      </Drawer>
    </div>
  );
};
