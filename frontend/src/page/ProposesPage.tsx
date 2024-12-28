import { Table, Input,Select } from 'antd';
import { useEffect, useState, useMemo} from 'react';
import * as proposeService from '../service/propose.service';
import { useNavigate } from 'react-router-dom';
import { Footer } from '../components/footer/Footer';
import { tabTitle } from '../utilities/title';
const { Option } = Select;
const { Search } = Input;
export const ProposesPage = () => {
  const [proposeList, setProposeList] = useState<any[]>([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [sortBy, setSortBy] = useState('id');
  const navigate = useNavigate();

  const loadPropose = async () => {
    const response = await proposeService.getPropose();
    if (response.data) {
      setProposeList(response.data.data);
    }
  };

  useEffect(() => {
    loadPropose();
  }, []);

  const handleRowClick = (record: any) => {
    navigate(`/manager-detail/${record.id}`);
  };

  const columns = [
    {
      title: 'ID',
      dataIndex: 'id',
      key: 'id',
    },
    {
      title: 'Tên phiếu',
      dataIndex: 'name',
      key: 'name',
    },
    {
      title: 'Trạng thái',
      dataIndex: 'status',
      key: 'status',
    },
    {
      title: 'Loại',
      dataIndex: 'type',
      key: 'type',
    },
    {
      title: 'Nhà kho',
      dataIndex: 'warehouse_name',
      key: 'warehouse_name',
    },
    {
      title: 'Mô tả',
      dataIndex: 'description',
      key: 'description',
    },
  ];
  const filteredProposeList = useMemo(() => {
    let filtered = [...proposeList];

    if (searchTerm) {
      filtered = filtered.filter((propose) =>
        propose.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        propose.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        propose.description.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    if (sortBy === "name") {
      filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === "name_desc") {
      filtered.sort((a, b) => b.name.localeCompare(a.name));
    }
    if (sortBy === "id") {
      filtered.sort((a, b) => a.id - b.id);
    }
    if (sortBy === "id_desc") {
      filtered.sort((a, b) => b.id - a.id);
    }

    return filtered;
  }, [proposeList, searchTerm, sortBy]);
  const handleSortChange = (value) => {
    setSortBy(value);
  };
  return (
    tabTitle('D2W - Danh sách đề xuất'),
    <div
      className="flex w-full justify-center bg-slate-300"
      style={{ height: 'calc(85vh)' }}
    >
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content">
      <div className="mb-5 flex items-center space-x-5">
    <Search
            placeholder="Tìm kiếm đề xuất"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            allowClear
            enterButton
            style={{ width: '300px' }}
          />
      <Select
        value={sortBy}
        onChange={handleSortChange}
        className="w-48 h-10"
      >
        <Option value="">Sắp xếp theo</Option>
        <Option value="name">Tên từ A-Z</Option>
        <Option value="name_desc">Tên từ Z-A</Option>
        <Option value="id">ID tăng dần</Option>
        <Option value="id_desc">ID giảm dần</Option>
      </Select>
    </div>
        <Table
          columns={columns}
          dataSource={filteredProposeList}
          rowKey="id"
          onRow={(record) => ({
            onClick: () => handleRowClick(record),
          })}
        />
      </div>
      <Footer />
    </div>
  );
};