import type { ISeoEntry } from "../../interfaces/ISeoEntry";
import api from "./http";

export async function getSeoEntry(path: string): Promise<ISeoEntry | null> {
  const response = await api.get<{ success: boolean; data: ISeoEntry | null }>(
    "store/seo-entry",
    { params: { path } },
  );
  return response.data.data;
}
