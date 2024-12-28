import { Card, Space } from 'antd';
import { ICardContent } from '../../common/interface';

export const CardContent = (props: ICardContent) => {
  return (
    <Card className={`w-full h-full  ${props.backgroundColor} cursor-pointer`} key={props.id}>
      <Space className='flex justify-center h-20'>
        <p className="font-semibold text-base">{props.content}:</p>
        <p className="font-semibold text-base">{props.count}</p>
      </Space>
    </Card>
  );
};
