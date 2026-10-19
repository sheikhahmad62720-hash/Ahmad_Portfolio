export interface Experience {
  id: number;
  company: string;
  position: string;
  location?: string;
  employment_type?: string;
  description?: string;
  start_date: string;
  end_date?: string;
  is_current: boolean;
}

export interface Education {
  id: number;
  institution: string;
  degree: string;
  field_of_study?: string;
  location?: string;
  description?: string;
  start_date: string;
  end_date?: string;
  is_current: boolean;
  grade?: string;
}

export interface Certificate {
  id: number;
  title: string;
  issuer: string;
  issue_date: string;
  expiry_date?: string;
  credential_id?: string;
  credential_url?: string;
  certificate_file?: string;
  description?: string;
  is_verified: boolean;
}
