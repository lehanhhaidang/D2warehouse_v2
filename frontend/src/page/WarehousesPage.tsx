import  { useEffect, useState, useMemo } from "react";
import { Table, Input, Select } from "antd";
import { Footer } from "../components/footer/Footer";
import * as warehouse from "../service/warehouse.service";
import { tabTitle } from "../utilities/title";

const { Option } = Select;
const { Search } = Input;

export const WarehousePage = () => {
  const [storages, setStorages] = useState<any[]>([]);
  const [sortBy, setSortBy] = useState("");
  const [searchTerm, setSearchTerm] = useState("");

  // Fetch warehouse data
  useEffect(() => {
    const loadData = async () => {
      const response = await warehouse.getAllWareHouse();
      setStorages(response.data.data);
    };
    loadData();
  }, []);

  // Filter and sort warehouse data
  const filteredStorages = useMemo(() => {
    let filtered = [...storages];

    // Filter based on search term
    if (searchTerm) {
      filtered = filtered.filter((storage) =>
        storage.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        storage.location.toLowerCase().includes(searchTerm.toLowerCase()) ||
        storage.category_name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Sort based on selected sort option
    if (sortBy === "name") {
      filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === "name_desc") {
      filtered.sort((a, b) => b.name.localeCompare(a.name));
    } else if (sortBy === "acreage") {
      filtered.sort((a, b) => a.acreage - b.acreage);
    } else if (sortBy === "acreage_desc") {
      filtered.sort((a, b) => b.acreage - a.acreage);
    } else if (sortBy === "number_of_shelf") {
      filtered.sort((a, b) => a.number_of_shelves - b.number_of_shelves);
    } else if (sortBy === "number_of_shelf_desc") {
      filtered.sort((a, b) => b.number_of_shelves - a.number_of_shelves);
    }

    return filtered;
  }, [storages, searchTerm, sortBy]);

  const handleSortChange = (value: string) => {
    setSortBy(value);
  };

  const columns = [
    { title: "ID", dataIndex: "id", key: "id" },
    { title: "Tên kho", dataIndex: "name", key: "name" },
    { title: "Vị trí", dataIndex: "location", key: "location" },
    { title: "Diện tích", dataIndex: "acreage", key: "acreage" },
    { title: "Số kệ có thể chứa", dataIndex: "number_of_shelves", key: "number_of_shelves" },
    { title: "Tên loại", dataIndex: "category_name", key: "category_name" },
    {
      title: "Ngày tạo",
      dataIndex: "created_at",
      key: "created_at",
      render: (date: any) => new Date(date).toLocaleDateString(),
    },
  ];

  return (
    tabTitle("D2W - Kho"),
    <div className="flex w-full justify-center bg-slate-300" style={{ height: "calc(85vh)" }}>
      <div className="flex flex-col w-4/5 pt-6 pb-10 px-4 bg-slate-100 scrollable-content">
        {/* Search and Sort controls */}
        <div className="mb-5 flex items-center space-x-5">
          <Search
            placeholder="Tìm kiếm kho"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            allowClear
            enterButton
            style={{ width: "300px" }}
          />
          <Select
            value={sortBy}
            onChange={handleSortChange}
            className="w-48 h-10"
          >
            <Option value="">Sắp xếp theo</Option>
            <Option value="name">Tên từ A-Z</Option>
            <Option value="name_desc">Tên từ Z-A</Option>
            <Option value="acreage">Diện tích tăng dần</Option>
            <Option value="acreage_desc">Diện tích giảm dần</Option>
            <Option value="number_of_shelf">Số kệ tăng dần</Option>
            <Option value="number_of_shelf_desc">Số kệ giảm dần</Option>
          </Select>
        </div>

        {/* Warehouse Table */}
        <div className="responsive-table">
          <Table
            columns={columns}
            dataSource={filteredStorages}
            rowKey="id"
          />
        </div>
      </div>
      <Footer />
    </div>
  );
};
