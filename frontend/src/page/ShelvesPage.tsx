import  { useEffect, useState, useMemo } from "react";
import { Table, Input, Select, Modal, Button, Pagination } from "antd";
import { Footer } from "../components/footer/Footer";
import * as shelveService from "../service/shelve.service";
import { tabTitle } from "../utilities/title";

const { Option } = Select;
const { Search } = Input;
export const ShelvesPage = () => {
  const [shelf, setShelf] = useState<any[]>([]);
  const [sortBy, setSortBy] = useState("id");
  const [searchTerm, setSearchTerm] = useState("");
  const [selectedShelf, setSelectedShelf] = useState<any | null>(null);
  const [shelfDetails, setShelfDetails] = useState<any[]>([]);
  const [modalOpen, setModalOpen] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [rowsPerPage, setRowsPerPage] = useState(10);

  

  // Fetch shelves data
  useEffect(() => {
    const fetchData = async () => {
      const shelvesData = await shelveService.getShevles();
      setShelf(shelvesData.data);
    };
    fetchData();
  }, []);

  // Filter and sort shelves data
  const filteredShelves = useMemo(() => {
    let filtered = [...shelf];

    if (searchTerm) {
      filtered = filtered.filter((shelves) =>
        shelves.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        shelves.category_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        shelves.warehouse_name.toLowerCase().includes(searchTerm.toLowerCase())
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
  }, [shelf, searchTerm, sortBy]);

  const handleSortChange = (value) => {
    setSortBy(value);
  };

  // Fetch shelf details when a row is clicked
  const handleRowClick = async (shelfId) => {
    const details = await shelveService.shelfDetail(shelfId);
    setShelfDetails(details.data || []);
    setSelectedShelf(shelfId);
    setModalOpen(true);
  };

  // Close the modal
  const handleCloseModal = () => {
    setModalOpen(false);
    setSelectedShelf(null);
  };

  const columns = [
    { title: "ID", dataIndex: "id", key: "id" },
    { title: "Tên kệ", dataIndex: "name", key: "name" },
    { title: "Số tầng", dataIndex: "number_of_levels", key: "number_of_levels" },
    { title: "Sức chứa", dataIndex: "storage_capacity", key: "storage_capacity" },
    { title: "Danh mục", dataIndex: "category_name", key: "category_name" },
    { title: "Kho", dataIndex: "warehouse_name", key: "warehouse_name" },
  ];

  return (
    tabTitle("D2W - Kệ"),
    <div className="flex w-full justify-center bg-slate-300" style={{ height: "calc(85vh)" }}>
  <div className="flex flex-col w-4/5 pt-6 pb-10 px-4 bg-slate-100 scrollable-content">
    {/* Search and Sort controls */}
    <div className="mb-5 flex items-center space-x-5">
    <Search
            placeholder="Tìm kiếm sản phẩm"
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

    {/* Shelf Table */}
    <div className="responsive-table">
      <Table
        columns={columns}
        dataSource={filteredShelves.slice(
          (currentPage - 1) * rowsPerPage,
          currentPage * rowsPerPage
        )}
        rowKey="id"
        onRow={(record) => ({
          onClick: () => handleRowClick(record.id),
        })}
        pagination={false} // Disable pagination from Table component
      />
    </div>

    {/* Pagination aligned to the right */}
    <div className="flex justify-end mt-4">
      <Pagination
        current={currentPage}
        pageSize={rowsPerPage}
        total={filteredShelves.length}
        onChange={(page, pageSize) => {
          setCurrentPage(page);
          setRowsPerPage(pageSize);
        }}
        showSizeChanger
        showTotal={(total) => `Tổng cộng ${total} kệ`}
      />
    </div>
  </div>

  {/* Modal for shelf details */}
<Modal
  title={`Thông tin kệ ${selectedShelf}`}
  open={modalOpen}
  onCancel={handleCloseModal}
  footer={[
    <Button key="close" onClick={handleCloseModal}>
      Đóng
    </Button>,
  ]}
>
  {shelfDetails.length === 0 ? (
    <p>Kệ này không chứa nguyên vật liệu hay thành phẩm nào.</p>
  ) : (
    <div>
      {shelfDetails.some(item => item.product_name) && (
        <Table
          className="responsive-table"
          columns={[
            { title: "Thành phẩm", dataIndex: "product_name", key: "product_name" },
            { title: "Số lượng", dataIndex: "quantity", key: "quantity" },
          ]}
          dataSource={shelfDetails.filter(item => item.product_name)}
          rowKey="product_id"
          pagination={false}
        />
      )}
      
      {shelfDetails.some(item => item.material_name) && (
        <Table
          className="responsive-table"
          columns={[
            { title: "Nguyên vật liệu", dataIndex: "material_name", key: "material_name" },
            { title: "Số lượng", dataIndex: "quantity", key: "quantity" },
          ]}
          dataSource={shelfDetails.filter(item => item.material_name)}
          rowKey="material_id"
          pagination={false}
        />
      )}
    </div>
  )}
</Modal>


  <Footer />
</div>

  );
};
