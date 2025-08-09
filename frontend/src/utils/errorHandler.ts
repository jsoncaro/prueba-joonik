export const handleApiError = (error: { message: string; status?: number }) => {
  console.error(`Error (${error.status}): ${error.message}`);
  return error.message;
};
