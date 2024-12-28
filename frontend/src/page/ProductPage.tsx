import { useEffect, useState, useMemo } from 'react';
import { Table, Input, Select, Image } from 'antd';
import * as productService from '../service/product.service';
import { Footer } from '../components/footer/Footer';
import { tabTitle } from '../utilities/title';

const { Option } = Select;
const { Search } = Input;

export const ProductPage = () => {
  const [products, setProducts] = useState([]);
  const [sortBy, setSortBy] = useState('');
  const [searchTerm, setSearchTerm] = useState('');

  const loadData = async () => {
    const response = await productService.getProducts();
    setProducts(response.data);
  };

  useEffect(() => {
    loadData();
  }, []);

  const handleSortChange = (value: string) => {
    setSortBy(value);
  };

  const filteredProducts = useMemo(() => {
    let filtered = [...products];

    // Filter products based on search term
    if (searchTerm) {
      filtered = filtered.filter((product) =>
        product.name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Sort products
    if (sortBy === 'name') {
      filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === 'name_desc') {
      filtered.sort((a, b) => b.name.localeCompare(a.name));
    } else if (sortBy === 'quantity') {
      filtered.sort((a, b) => a.quantity - b.quantity);
    } else if (sortBy === 'quantity_desc') {
      filtered.sort((a, b) => b.quantity - a.quantity);
    }

    return filtered;
  }, [products, searchTerm, sortBy]);

  const columns = [
    {
      title: 'ID',
      dataIndex: 'id',
      key: 'id',
    },
    {
      title: 'Hình ảnh',
      dataIndex: 'product_img',
      key: 'product_img',
      render: (src: string) => (
        <Image src={src} alt="Product" width={70} height={70} />
      ),
    },
    {
      title: 'Tên sản phẩm',
      dataIndex: 'name',
      key: 'name',
    },
    {
      title: 'Đơn vị',
      dataIndex: 'unit',
      key: 'unit',
    },
    {
      title: 'Số lượng',
      dataIndex: 'quantity',
      key: 'quantity',
    },
  ];

  return (
    tabTitle('D2W - Thành phẩm'),
    <div
      className="flex w-full justify-center bg-slate-300"
      style={{ height: 'calc(85vh)' }}
    >
      <div className="flex flex-col w-4/5 pt-6 pb-10 px-4 bg-slate-100 scrollable-content">
        <div style={{ marginBottom: '20px', display: 'flex', gap: '20px' }}>
          {/* Search input */}
          <Search
            placeholder="Tìm kiếm sản phẩm"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            allowClear
            enterButton
            style={{ width: '300px' }}
          />

          {/* Sort select */}
          <Select
            value={sortBy}
            onChange={handleSortChange}
            placeholder="Sắp xếp theo"
            style={{ width: '200px' }}
          >
            <Option value="name">Tên từ A-Z</Option>
            <Option value="name_desc">Tên từ Z-A</Option>
            <Option value="quantity">Số lượng tăng dần</Option>
            <Option value="quantity_desc">Số lượng giảm dần</Option>
          </Select>
        </div>

        {/* Table */}
        <Table
          dataSource={filteredProducts}
          columns={columns}
          rowKey="id"
          pagination={{ pageSize: 10 }}
        />
      </div>
      <Footer />
    </div>
  );
};
