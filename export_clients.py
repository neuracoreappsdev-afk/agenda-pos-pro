import openpyxl
import json
import sys

try:
    wb = openpyxl.load_workbook(r'C:\Users\imper\Downloads\listado de clientes L.xlsx', data_only=True)
    sheet = wb.active
except Exception as e:
    print(json.dumps({"error": str(e)}))
    sys.exit(1)

rows = list(sheet.rows)
if not rows:
    print(json.dumps([]))
    sys.exit(0)

header = [str(cell.value) for cell in rows[0]]
data = []

for row in rows[1:]:
    item = {}
    for i, cell in enumerate(row):
        if i < len(header):
            val = cell.value
            if val is None:
                val = ""
            item[header[i]] = str(val)
    if any(item.values()): # Skip empty rows
        data.append(item)

print(json.dumps(data))
