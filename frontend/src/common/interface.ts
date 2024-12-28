export interface INavBar {
  id: number;
  label: string;
  url: string;
  icon: any;
  children?: INavBar[];
}

export interface ICardContent {
  id: number;
  backgroundColor: string;
  content: string;
  count: number;
  url: string;
}

export interface IUser {
  id: number;
  name: string;
  email: string;
  phone: string;
  img_url: string;
  status: number;
  email_verified_at: string;
  created_at: string;
  updated_at: string;
  role_id: number;
}

export interface MaterialEntry {
  id: any;
  material_id?: number;
  unit?: string;
  quantity?: number;
  product_id?: number;
  shelf_id?: number;
  note?: string;
}