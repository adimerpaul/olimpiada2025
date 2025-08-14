// src/utils/inscripcion-pdf.js
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'
import QRCode from 'qrcode'

export async function generarPDFInscripcion ({
                                               inscrito,
                                               area,
                                               integrantes = [],
                                               baseUrl = '',
                                               assets = { left: '/images.png', right: '/logo_fni.png' }
                                             }) {
  const doc = new jsPDF({ unit: 'mm', format: 'a4' })
  const W = doc.internal.pageSize.getWidth()
  const H = doc.internal.pageSize.getHeight()

  // Estilo base
  const PRIMARY = [25, 118, 210]   // #1976d2
  const M = 14                     // margen lateral
  const TOP = 22                   // alto de header
  const GAP = 6

  // Helpers
  const toDataURL = async (url) => {
    if (!url) return null
    try {
      const res = await fetch(url)
      const blob = await res.blob()
      return await new Promise(resolve => {
        const fr = new FileReader()
        fr.onload = () => resolve(fr.result)
        fr.readAsDataURL(blob)
      })
    } catch { return null }
  }
  const clampRight = (text, maxWidth) => {
    if (!text) return ''
    let t = String(text)
    while (doc.getTextWidth(t) > maxWidth && t.length > 1) {
      t = t.slice(0, -2) + '…'
    }
    return t
  }

  // Recursos
  const [imgLeft, imgRight, qrDataUrl] = await Promise.all([
    toDataURL(assets.left),
    toDataURL(assets.right),
    QRCode.toDataURL(JSON.stringify({
      id: inscrito?.id,
      area: area?.area,
      grupo: inscrito?.grupo_nombre || '',
      ts: new Date().toISOString()
    }))
  ])

  // HEADER
  doc.setFillColor(...PRIMARY)
  doc.rect(0, 0, W, TOP, 'F')
  if (imgLeft) doc.addImage(imgLeft, 'PNG', M - 4, 4, 14, 14)
  if (imgRight) doc.addImage(imgRight, 'PNG', W - (M + 10), 4, 14, 14)
  doc.setTextColor('#fff')
  doc.setFontSize(12)
  doc.text('VIII OLIMPIADA DE', W / 2, 8, { align: 'center' })
  doc.setFontSize(16)
  doc.text('CIENCIA Y TECNOLOGÍA F.N.I.', W / 2, 16, { align: 'center' })

  // TÍTULO + META
  let y = TOP + 10
  doc.setTextColor('#000')
  doc.setFontSize(14)
  doc.text('Constancia de Inscripción', M, y)
  y += 6

  const fecha = inscrito?.created_at ? new Date(inscrito.created_at) : new Date()
  const fechaStr = fecha.toLocaleDateString('es-BO', { year: 'numeric', month: 'long', day: 'numeric' })
  doc.setFontSize(10)
  doc.text(`Fecha: ${fechaStr}`, M, y)
  y += 5
  doc.text(`ID de inscripción: ${inscrito?.id || '-'}`, M, y)
  y += GAP

  // CAJA DE ÁREA (con límites y clamp en "Lugar")
  const boxH = 20
  doc.setDrawColor(...PRIMARY)
  doc.setFillColor(245, 248, 255)
  doc.roundedRect(M, y, W - 2 * M, boxH + 4, 2, 2, 'FD')

  doc.setFontSize(11)
  let innerY = y + 8
  doc.text(`Área: ${area?.area || '-'}`, M + 6, innerY)
  innerY += 6
  doc.text(`Modalidad: ${area?.modalidad || '-'}`, M + 6, innerY)

  // Lugar a la derecha con recorte
  if (area?.lugar) {
    const rightMax = (W - M - 6) - (M + 6)  // ancho disponible
    const lugar = clampRight(`Lugar: ${area.lugar}`, rightMax)
    doc.text(lugar, W - M - 6, innerY, { align: 'right' })
  }
  innerY += 6
  if (inscrito?.grupo_nombre) {
    doc.text(`Grupo: ${inscrito.grupo_nombre}`, M + 6, innerY)
  }

  y += boxH + GAP

  // TABLA
  const body = (integrantes || []).map((i, idx) => [
    idx + 1,
    i.nombre || '',
    i.ci || '',
    i.curso || '',
    i.tutor || '',
    i.telefono || ''
  ])

  autoTable(doc, {
    startY: y,
    margin: { top: TOP + 4, left: M, right: M, bottom: 28 },
    head: [['#', 'Nombre', 'CI', 'Curso', 'Tutor', 'Teléfono']],
    body,
    theme: 'grid',
    styles: { fontSize: 9, cellPadding: 2, overflow: 'linebreak' },
    headStyles: { fillColor: PRIMARY, halign: 'center' },
    columnStyles: {
      0: { cellWidth: 8, halign: 'center' },    // #
      1: { cellWidth: 62 },                     // Nombre
      2: { cellWidth: 28 },                     // CI
      3: { cellWidth: 30 },                     // Curso
      4: { cellWidth: 32 },                     // Tutor
      5: { cellWidth: 30 }                      // Teléfono
    }
  })

  // POSICIÓN FINAL
  y = (doc.lastAutoTable?.finalY || y) + GAP

  // Si no entra el bloque de QR/firma, crear nueva página
  const BLOCK_H = 34
  if (y + BLOCK_H > H - M) {
    doc.addPage()
    y = M
  }

  // QR + PIE
  if (qrDataUrl) doc.addImage(qrDataUrl, 'PNG', M, y, 28, 28)
  doc.setFontSize(9)
  const urlVerif = (baseUrl ? baseUrl.replace(/\/+$/, '') : '') + `/inscripciones/${inscrito?.id || ''}`
  doc.text('Escanee el QR para validar esta inscripción.', M + 32, y + 10)
  // clamp de URL para que no se salga
  const urlClamped = clampRight(urlVerif, W - (M + 32) - M)
  doc.text(urlClamped, M + 32, y + 16)

  // Línea de firma a la derecha
  const lineX1 = W - (M + 56)
  const lineX2 = W - M
  const lineY  = y + 22
  doc.line(lineX1, lineY, lineX2, lineY)
  doc.text('Firma y sello', (lineX1 + lineX2) / 2, lineY + 6, { align: 'center' })

  // Guardar
  doc.save(`inscripcion_${inscrito?.id || Date.now()}.pdf`)
}
