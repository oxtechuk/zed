import { Star } from "lucide-react";

interface RatingStarsProps {
  fillColor?: string;
  rating?: number;
}

export default function RatingStars({ fillColor = "#FFB800", rating = 5 }: RatingStarsProps) {
  return (
    <div className="flex items-center gap-1">
      {Array.from({ length: 5 }).map((_, index) => {
        const isFilled = index < rating;
        return (
          <Star
            key={index}
            size={16}
            fill={isFilled ? fillColor : "transparent"}
            stroke={fillColor}
            strokeWidth={isFilled ? 0 : 1.5}
            className="transition-all duration-300"
          />
        );
      })}
    </div>
  );
}
