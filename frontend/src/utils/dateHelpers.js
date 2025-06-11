import dayjs from 'dayjs';

export function formatDate(dateInput, outputFormat = 'MMMM D, YYYY', inputFormat = null) {
  if (!dateInput) return null;

  // If inputFormat is given, parse dateInput as that format string
  // Otherwise dayjs tries to parse automatically
  const parsedDate = inputFormat ? dayjs(dateInput, inputFormat) : dayjs(dateInput);

  // If invalid date, return null or empty string
  if (!parsedDate.isValid()) return null;

  // Format to outputFormat
  return parsedDate.format(outputFormat);
}

// Optional helper to format Date or dayjs object back to DB string (ISO Date)
// Default DB format: 'YYYY-MM-DD'
export function toDBFormat(dateInput, dbFormat = 'YYYY-MM-DD') {
  if (!dateInput) return null;

  const parsedDate = dayjs(dateInput);

  if (!parsedDate.isValid()) return null;

  return parsedDate.format(dbFormat);
}
