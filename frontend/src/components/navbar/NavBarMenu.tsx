import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { INavBar } from '../../common/interface';
import * as dataNavBar from '../../data/navbar';
import { Menu } from 'antd';
import { DownOutlined } from '@ant-design/icons';

interface NavBarMenuProps {
  roleId: number;
  activeKey: string | null;
  onClose: () => void;
}

const NavBarMenu: React.FC<NavBarMenuProps> = ({ roleId, activeKey, onClose }) => {
  const navigate = useNavigate();
  const [openMenu, setOpenMenu] = useState<Set<number>>(new Set());

  // Toggle open/close của menu con
  const handleToggleMenu = (id: number) => {
    setOpenMenu((prev) => {
      const newOpenMenu = new Set(prev);
      if (newOpenMenu.has(id)) {
        newOpenMenu.delete(id); 
      } else {
        newOpenMenu.add(id); 
      }
      return newOpenMenu;
    });
  };

  const renderMenuItem = (item: INavBar) => {
    const Icon = item.icon;
    const isActive = item.id.toString() === activeKey;
    
    return (
      <Menu.Item
        key={item.id}
        icon={Icon && <Icon className="text-xl" />}
        className={`${isActive ? 'bg-sky-100 text-sky-700' : ''}`}
        onClick={() => {
          if (item.children) {
            handleToggleMenu(item.id); 
          } else {
            navigate(item.url);
            onClose();
          }
        }}
      >
        <span>{item.label}</span>
        {item.children && <DownOutlined className="ml-2" />}
      </Menu.Item>
    );
  };

  const renderMenu = (items: INavBar[]) => {
    return items.map((item) => {
      if (item.children && item.children.length > 0) {
        return (
          <Menu.SubMenu
            key={item.id}
            icon={item.icon && <item.icon />}
            title={item.label}
            onTitleClick={() => handleToggleMenu(item.id)}
          >
            {renderMenu(item.children)}
          </Menu.SubMenu>
        );
      }
      return renderMenuItem(item);
    });
  };

  return (
    <Menu
      mode="inline"
      defaultSelectedKeys={[activeKey || '']}
      defaultOpenKeys={['sub1']} 
      onClick={(e) => {
        console.log('click', e);
      }}
    >
      {renderMenu(dataNavBar.getNavBarItems(roleId))}
    </Menu>
  );
};

export default NavBarMenu;
