import { Card, Space } from 'antd';
import { ICardContentMaterial } from '../../common/interface';

export const CardContentMaterial = (props: ICardContentMaterial) => {
  return (
    <Card className={`w-full h-full ${props.backgroundColor} cursor-pointer bg-blue-100`} key={props.id}>
      <Space className='flex flex-col justify-center items-center h-full'>
        <div className="mb-4 w-80 h-80 flex items-center justify-center bg-white ">
          <img src={props.image} alt={props.name} className="object-cover w-full h-full" />
        </div>
        <p className="font-semibold text-base">Mã: {props.idmaterial}</p>
        <p className="font-semibold text-base">Tên: {props.name}</p>
        <p className="font-semibold text-base">Số lượng tồn: {props.quantity}</p>
        <p className="font-semibold text-base">Đơn vị: {props.unit}</p>
      </Space>
    </Card>
  );
};
