import  { useEffect, useState, useMemo } from "react";
import { Table, Modal, Button, Pagination, Select } from "antd";
import { getNotes } from "../service/dashboard.service"; // Import hàm getNotes
import { Footer } from "../components/footer/Footer";
import { Link, useSearchParams } from "react-router-dom"; // Import useSearchParams
import { tabTitle } from "../utilities/title";

export const ImportExportNotes = () => {
  const [importExportNotes, setImportExportNotes] = useState<any[]>([]);
  const [selectedNote, setSelectedNote] = useState<any | null>(null);
  const [noteDetails, setNoteDetails] = useState<any[]>([]);
  const [modalOpen, setModalOpen] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [rowsPerPage, setRowsPerPage] = useState(10);
  const [filterType, setFilterType] = useState<string>("all"); // New state to track filter

  const [searchParams, setSearchParams] = useSearchParams();

  // Effect to update filter type based on the URL query parameter
  useEffect(() => {
    const filterFromUrl = searchParams.get("filter");
    if (filterFromUrl) {
      setFilterType(filterFromUrl);
    }
  }, [searchParams]);

  useEffect(() => {
    const fetchData = async () => {
      const notesData = await getNotes();
      const updatedNotes = notesData.map((note, index) => ({
        ...note,
        uniqueId: `${note.id}-${note.propose_id}-${index}`,
        displayDate: note.receive_date || note.export_date,
        dateType: note.receive_date ? "receive_date" : "export_date",
        displayId: index + 1,
      }));
      setImportExportNotes(updatedNotes);
    };
    fetchData();
  }, []);

  // Handle filter change
  const handleFilterChange = (value: string) => {
    setFilterType(value);
    setCurrentPage(1); // Reset to first page on filter change
    setSearchParams({ filter: value }); // Update URL query parameter
  };

  // Filter notes based on selected type
  const filteredNotes = useMemo(() => {
    if (filterType === "all") {
      return importExportNotes;
    }
    return importExportNotes.filter(note =>
      note.name.toLowerCase().includes(filterType.toLowerCase())
    );
  }, [importExportNotes, filterType]);

  const handleRowClick = (uniqueId: string) => {
    const selected = importExportNotes.find(note => note.uniqueId === uniqueId);
    setNoteDetails(selected?.details || []);
    setSelectedNote(selected);
    setModalOpen(true);
  };

  const handleCloseModal = () => {
    setModalOpen(false);
    setSelectedNote(null);
  };

const handlePrint = () => {
  const printWindow = window.open("", "_blank", "width=800,height=600");
  if (printWindow) {
    printWindow.document.write(`
      <html>
        <head>
          <style>
            body {
              font-family: 'Arial', sans-serif;
              background-color: #f4f4f9;
              margin: 0;
              padding: 0;
            }
            .container {
              width: 90%;
              margin: 0 auto;
              padding: 20px;
              background-color: #ffffff;
              box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
              border-radius: 8px;
            }
            .title {
              text-align: center;
              font-size: 28px;
              font-weight: bold;
              color: #333;
              margin-bottom: 20px;
              border-bottom: 2px solid #e0e0e0;
              padding-bottom: 10px;
            }
            .info {
              font-size: 16px;
              margin-bottom: 15px;
              color: #555;
            }
            .info strong {
              color: #333;
            }
            .table {
              width: 100%;
              border-collapse: collapse;
              margin-top: 20px;
              background-color: #fafafa;
            }
            .table th, .table td {
              border: 1px solid #ddd;
              padding: 12px;
              text-align: left;
              font-size: 14px;
              color: #555;
            }
            .table th {
              background-color: #e0e0e0;
              font-weight: bold;
            }
            .table td {
              background-color: #ffffff;
            }
            .table tbody tr:nth-child(even) {
              background-color: #f9f9f9;
            }
            .signature-section {
              margin-top: 30px;
              display: flex;
              justify-content: space-between;
              padding-bottom: 150px;
              border-top: 2px solid #e0e0e0;
            }

            .signature {
              width: 30%;
              text-align: center;
              font-size: 16px;
              color: #555;
              padding-top: 20px;  /* Thêm khoảng cách phía trên mỗi phần ký */
            }

            .signature p {
              margin: 5px 0;
            }

            .signature strong {
              font-weight: bold;
            }
            @media print {
              body {
                background-color: #ffffff;
                margin: 0;
              }
              .container {
                width: 100%;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
              }
            }
          </style>
        </head>
        <body>
          <div class="container">
            <div class="title">${selectedNote?.name}</div>
            <div class="info"><strong>Tên phiếu:</strong> ${selectedNote?.name}</div>
            <div class="info"><strong>Kho:</strong> ${selectedNote?.warehouse_name}</div>
            <div class="info"><strong>Ngày:</strong> ${selectedNote?.displayDate}</div>
            <div class="info"><strong>Người tạo:</strong> ${selectedNote?.created_by_name}</div>
            ${
              selectedNote?.name.includes("thành phẩm") ? ` 
                <h3>Chi Tiết Thành Phẩm</h3>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Thành phẩm</th>
                      <th>Số lượng</th>
                      <th>Kệ lưu trữ</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${noteDetails
                      .filter(item => item.product_name)
                      .map(item => `
                        <tr>
                          <td>${item.product_name}</td>
                          <td>${item.quantity}</td>
                          <td>${item.shelf_name}</td>
                        </tr>`).join('')}
                  </tbody>
                </table>
              ` : ""
            }
            ${
              selectedNote?.name.includes("nguyên vật liệu") ? `
                <h3>Chi Tiết Nguyên Vật Liệu</h3>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Nguyên vật liệu</th>
                      <th>Số lượng</th>
                      <th>Kệ lưu trữ</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${noteDetails
                      .filter(item => item.material_name)
                      .map(item => `
                        <tr>
                          <td>${item.material_name}</td>
                          <td>${item.quantity}</td>
                          <td>${item.shelf_name}</td>
                        </tr>`).join('')}
                  </tbody>
                </table>
              ` : ""
            }
            <div class="signature-section">
              <div class="signature">
                <p><strong>Người lập phiếu</strong></p>
                <p>(Ký và đóng dấu)</p>
              </div>
              <div class="signature">
                <p><strong>Quản lý kho</strong></p>
                <p>(Ký và đóng dấu)</p>
              </div>
              <div class="signature">
                <p><strong>Giám đốc</strong></p>
                <p>(Ký và đóng dấu)</p>
              </div>
            </div>
          </div>
        </body>
      </html>
    `);
    printWindow.document.close();
    printWindow.print();
  }
  };
  


  const columns = [
    { title: "STT", dataIndex: "displayId", key: "displayId" },
    { title: "Tên phiếu", dataIndex: "name", key: "name" },
    { title: "Kho", dataIndex: "warehouse_name", key: "warehouse_name" },
    { title: "Người tạo phiếu", dataIndex: "created_by_name", key: "created_by_name" },
    {
      title: "Ngày",
      dataIndex: "displayDate",
      key: "displayDate",
      render: (text, record) => (
        <span>{record.dateType === "receive_date" ? record.receive_date : record.export_date}</span>
      ),
    },
  ];

  const productColumns = [
    { title: "Thành phẩm", dataIndex: "product_name", key: "product_name" },
    { title: "Số lượng", dataIndex: "quantity", key: "quantity" },
    { title: "Kệ lưu trữ", dataIndex: "shelf_name", key: "shelf_name" },
  ];

  const materialColumns = [
    { title: "Nguyên vật liệu", dataIndex: "material_name", key: "material_name" },
    { title: "Số lượng", dataIndex: "quantity", key: "quantity" },
    { title: "Kệ lưu trữ", dataIndex: "shelf_name", key: "shelf_name" },
  ];

  return (
    tabTitle("D2W - Phiếu nhập xuất"),
    <div className="flex w-full justify-center bg-slate-300" style={{ height: "calc(85vh)" }}>
      <div className="flex flex-col w-4/5 pt-6 pb-10 px-4 bg-slate-100 scrollable-content">
        {/* Filter Dropdown */}
        <div className="flex justify-between mb-4">
          <Select
            value={filterType}
            style={{ width: 200 }}
            onChange={handleFilterChange}
          >
            <Select.Option value="all">Tất cả</Select.Option>
            <Select.Option value="nhập thành phẩm">Nhập thành phẩm</Select.Option>
            <Select.Option value="xuất thành phẩm">Xuất thành phẩm</Select.Option>
            <Select.Option value="nhập nguyên vật liệu">Nhập nguyên vật liệu</Select.Option>
            <Select.Option value="xuất nguyên vật liệu">Xuất nguyên vật liệu</Select.Option>
          </Select>
        </div>

        {/* Table for Import/Export Notes */}
        <div className="responsive-table">
          <Table
            columns={columns}
            dataSource={filteredNotes.slice(
              (currentPage - 1) * rowsPerPage,
              currentPage * rowsPerPage
            )}
            rowKey="uniqueId"
            onRow={(record) => ({
              onClick: () => handleRowClick(record.uniqueId),
            })}
            pagination={false}
          />
        </div>

        {/* Pagination */}
        <div className="flex justify-end mt-4">
          <Pagination
            current={currentPage}
            pageSize={rowsPerPage}
            total={filteredNotes.length}
            onChange={(page, pageSize) => {
              setCurrentPage(page);
              setRowsPerPage(pageSize);
            }}
            showSizeChanger
            showTotal={(total) => `Tổng cộng ${total} phiếu`}
          />
        </div>
      </div>

      <Modal 
        title={<div style={{ textAlign: 'center' , fontSize:'20px'}}>{`Chi tiết  ${selectedNote?.name}`}</div>}
        open={modalOpen}
        onCancel={handleCloseModal}
        footer={[
          <Button key="print" onClick={handlePrint} className="bg-blue-500 text-white hover:bg-blue-600 rounded px-4 py-2">
            In
          </Button>,
          <Button key="close" onClick={handleCloseModal} className="bg-gray-500 text-white hover:bg-gray-600 rounded px-4 py-2">
            Đóng
          </Button>,
        ]}
        width={800}
        className="p-6"
      >
  {noteDetails.length === 0 ? (
    <p className="text-center text-gray-500">Phiếu này không chứa chi tiết nào.</p>
  ) : (
    <div>
        <div className="mb-6 space-y-4">
          <div><strong className="font-semibold text-gray-700">Tên phiếu:</strong> {selectedNote?.name}</div>
          <div><strong className="font-semibold text-gray-700">Kho:</strong> {selectedNote?.warehouse_name}</div>
          <div><strong className="font-semibold text-gray-700">Ngày:</strong> {selectedNote?.displayDate}</div>
          <div><strong className="font-semibold text-gray-700">Người tạo:</strong> {selectedNote?.created_by_name}</div>

          {/* Kiểm tra và hiển thị thông tin Kế hoạch hoặc Đơn hàng */}
          {selectedNote?.name.includes("nhập thành phẩm") || selectedNote?.name.includes("xuất nguyên vật liệu") ? (
            <div><strong className="font-semibold text-gray-700">Kế hoạch: </strong>
              <Link to={`/manufacturing-detail/${selectedNote?.manufacturing_plan_id}`} className="font-semibold text-blue-700">
                {selectedNote?.manufacturing_plan_name}
              </Link>
            </div>
          ) : null}

          {selectedNote?.name.includes("xuất thành phẩm") ? (
            <div><strong className="font-semibold text-gray-700">Đơn hàng: </strong>
              <Link to={'/orders'} className="font-semibold text-blue-700">
                {selectedNote?.order_name}
              </Link>
            </div>
          ) : null}
        </div>
      {/* Details for "Thành Phẩm" */}
      {selectedNote?.name.includes("thành phẩm") && noteDetails.some(item => item.product_name) && (
        <div className="mb-6">
          <h3 className="text-sm font-semibold text-gray-800 mb-4">Chi Tiết Thành Phẩm</h3>
          <Table
            className="w-full text-sm text-left border-collapse"
            columns={productColumns}
            dataSource={noteDetails.filter(item => item.product_name)}
            rowKey="product_id"
            pagination={false}
          />
        </div>
      )}

      {/* Details for "Nguyên Vật Liệu" */}
      {selectedNote?.name.includes("nguyên vật liệu") && noteDetails.some(item => item.material_name) && (
        <div>
          <h3 className="text-sm font-semibold text-gray-800 mb-4">Chi Tiết Nguyên Vật Liệu</h3>
          <Table
            className="w-full text-sm text-left border-collapse"
            columns={materialColumns}
            dataSource={noteDetails.filter(item => item.material_name)}
            rowKey="material_id"
            pagination={false}
          />
        </div>
      )}
    </div>
  )}
</Modal>



      <Footer />
    </div>
  );
};
