export interface Listing {
  id: string;
  name: string;
  phone: string;
  link: string;
  region: string;
  subRegion?: string;
  description: string;
  rating: number;
  reviewCount: number;
  imageUrl: string;
}

export interface Region {
  id: string;
  name: string;
  icon: string;
}
