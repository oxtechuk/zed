export interface ISeoEntryFaq {
  q: string;
  a: string;
}

export interface ISeoEntry {
  meta_title: string | null;
  meta_description: string | null;
  intro: string | null;
  faq: ISeoEntryFaq[];
  image_alt: string | null;
}
