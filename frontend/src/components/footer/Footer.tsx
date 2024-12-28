import {
    GithubOutlined,
  InstagramOutlined,
  LinkedinOutlined,
  YoutubeOutlined,
} from '@ant-design/icons';
import './style.css';
import { Space } from 'antd';
export const Footer = () => {
  return (
    <div className="w-full footer-background flex flex-row">
      <div className="w-full flex items-center justify-center text-lg font-semibold p-6">
          © 2024 Storage Management -  D2WAREHOUSE  
        </div>
      <div className="w-full p-4 justify-end flex">
      
        <Space>
          <LinkedinOutlined className='text-3xl'/>
          <InstagramOutlined className='text-3xl'/>
          <YoutubeOutlined className='text-3xl'/>
          <GithubOutlined className='text-3xl'/>
        </Space>
      </div>
    </div>
  );
};
