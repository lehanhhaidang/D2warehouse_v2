
import image from '../assets/img1.jpg'; 
import { tabTitle } from '../utilities/title';

export const AdminIndex = () => {
    return (
        tabTitle("D2warehouse - Trang chủ"),
        <div className="flex w-full justify-center" style={{ height: 'calc(85vh)' }}>
            <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-200">
                <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '80vh' }}>
                    <img src={image} alt="Admin" className="highlighted-image" style={{ maxWidth: '100%', height: 'auto' }} /> {/* Hiển thị hình ảnh */}
                </div>
            </div>
        </div>
    );
};