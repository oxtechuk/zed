export interface IMediaLightboxModalProps {
  activeMedia: { type: "video" | "image"; url: string } | null;
  onClose: () => void;
}
