import React from "react";
import { Card, CardContent, CardMedia, Typography } from "@mui/material";

interface LocationCardProps {
  location: {
    id: number;
    name: string;
    code: string;
    image?: string;
  };
}

const LocationCard: React.FC<LocationCardProps> = ({ location }) => {
  return (
    <Card>
      {location.image && (
        <CardMedia
          component="img"
          height="140"
          image={location.image}
          alt={location.name}
        />
      )}
      <CardContent>
        <Typography variant="h6">{location.name}</Typography>
        <Typography color="text.secondary">{location.code}</Typography>
      </CardContent>
    </Card>
  );
};

export default LocationCard;
