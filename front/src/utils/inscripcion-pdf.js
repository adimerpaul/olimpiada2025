// src/utils/inscripcion-pdf.js
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'
import QRCode from 'qrcode'

export function generarPDFInscripcionMat (opts = {}) {
  const tutor       = opts.tutor || {}
  const colegio     = opts.colegio || {}
  const grado       = opts.grado || ''
  // ⬇️ sin tope (imprime todos); si quieres limitar a 10, usa .slice(0, 10)
  const estudiantes = Array.isArray(opts.estudiantes) ? opts.estudiantes : []

  const doc = new jsPDF({ unit: 'mm', format: 'a4' })
  const W = doc.internal.pageSize.getWidth()
  const L = 16   // margen izquierdo
  const R = 16   // margen derecho
  let y  = 18

  const drawHLine = (x1, x2, yy) => doc.line(x1, yy, x2, yy)
  const drawUnderlinedField = (x, yy, w) => drawHLine(x, x + w, yy)

  // Encabezado principal
  doc.setFont('helvetica', 'normal')
  doc.setFontSize(8)
  doc.text('EL CONTENIDO ES ACUMULATIVO. CADA PROBLEMA DE OLIMPIADA PUEDE ABARCAR DOS O MÁS TEMAS DEL CONTENIDO Y', W / 2, y, { align: 'center' })
  y += 4
  doc.text('HACE ÉNFASIS EN RAZONAMIENTO MATEMÁTICO.', W / 2, y, { align: 'center' })

  y += 6
  doc.setFontSize(11)
  doc.text('OLIMPIADAS DE CIENCIA Y TECNOLOGÍA F.N.I.', W / 2, y, { align: 'center' })
  y += 6
  doc.setFont('helvetica', 'bold')
  doc.text('OLIMPIADA MATEMÁTICA “FUNDACIÓN DE ORURO”', W / 2, y, { align: 'center' })

  y += 7
  doc.setFontSize(10)
  doc.setFont('helvetica', 'bold')
  doc.text('FORMULARIO ESCRITO PARA REGISTRO DE PARTICIPANTES', W / 2, y, { align: 'center' })
  y += 5
  doc.setFont('helvetica', 'normal')
  doc.setFontSize(8)
  doc.text('(ESTE FORMULARIO PUEDE SER LLENADO CON ESCRITURA A MANO)', W / 2, y, { align: 'center' })

  // línea separadora larga
  y += 6
  drawHLine(L, W - R, y)

  // Bloque: DATOS DEL PROFESOR(A) TUTOR(A)
  y += 6
  doc.setFillColor(0,0,0)
  doc.setTextColor(255,255,255)
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(9)
  doc.rect(L, y - 4.5, W - L - R, 6, 'F')
  doc.text('DATOS DEL PROFESOR(A) TUTOR(A)', L + 2, y)

  // Sub-líneas Apellido Paterno | Apellido Materno | Nombres
  y += 8
  doc.setTextColor(0,0,0)
  doc.setFont('helvetica', 'normal')
  doc.setFontSize(9)

  const colW = (W - L - R)
  const col1W = colW * 0.28
  const col2W = colW * 0.28
  const col3W = colW * 0.36
  const gap  = colW * 0.02
  let x = L

  // Campos con líneas
  doc.text(tutor.paterno || '', x + 1, y)
  drawUnderlinedField(x, y + 2, col1W); x += col1W + gap
  doc.text(tutor.materno || '', x + 1, y)
  drawUnderlinedField(x, y + 2, col2W); x += col2W + gap
  doc.text(tutor.nombres || '', x + 1, y)
  drawUnderlinedField(x, y + 2, col3W)

  // Etiquetas
  y += 6
  x = L
  doc.setFontSize(7.8)
  doc.text('Apellido Paterno', x + col1W / 2, y, { align: 'center' })
  doc.text('Apellido Materno', L + col1W + gap + col2W / 2, y, { align: 'center' })
  doc.text('Nombres', L + col1W + gap + col2W + gap + col3W / 2, y, { align: 'center' })

  // CELULAR / CORREO
  y += 7
  doc.setFontSize(9)
  x = L
  const celW = colW * 0.28
  const corW = colW * 0.68

  doc.text('CELULAR:', x, y)
  drawUnderlinedField(x + 18, y + 2, celW - 18)
  if (tutor.celular) doc.text(String(tutor.celular), x + 19, y)
  x += celW + gap

  doc.text('CORREO ELECTRÓNICO:', x, y)
  drawUnderlinedField(x + 35, y + 2, corW - 35)
  if (tutor.email) doc.text(String(tutor.email), x + 45, y)
  y += 5
  doc.setFontSize(7.8)
  doc.text('Número', L + 18 + (celW - 18) / 2, y, { align: 'center' })
  doc.text('(El correo debe pertenecer a GMAIL)', L + celW + gap + 35 + (corW - 35) / 2, y, { align: 'center' })

  // Bloque: DATOS UNIDAD EDUCATIVA… (UE, Localidad, Grado)
  y += 8
  doc.setFillColor(0,0,0)
  doc.setTextColor(255,255,255)
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(9)
  doc.rect(L, y - 4.5, W - L - R, 6, 'F')
  doc.text('DATOS UNIDAD EDUCATIVA, GRADO Y ESTUDIANTES', L + 2, y)

  // Línea: NOMBRE DE LA UNIDAD, LOCALIDAD/CIUDAD, GRADO
  y += 8
  doc.setTextColor(0,0,0)
  doc.setFont('helvetica', 'normal')
  doc.setFontSize(9)

  const wTotal = W - L - R
  const wNombreUE = wTotal * 0.55
  const wLocalidad = wTotal * 0.17
  const wGrado = wTotal * 0.24
  x = L

  // Nombre UE
  drawUnderlinedField(x, y + 2, wNombreUE)
  if (colegio.nombre) doc.text(colegio.nombre, x + 1, y)
  // Localidad
  drawUnderlinedField(x + wNombreUE + 4, y + 2, wLocalidad - 4)
  if (colegio.localidad) doc.text(colegio.localidad, x + wNombreUE + 5, y)
  // Grado
  drawUnderlinedField(L + wTotal - wGrado, y + 2, wGrado)
  if (grado) doc.text(grado, L + wTotal - wGrado + 1, y)

  // Etiquetas (sin solape en GRADO)
  y += 6
  doc.setFontSize(7.8)
  doc.text('NOMBRE DE LA UNIDAD EDUCATIVA', L + wNombreUE / 2, y, { align: 'center' })
  doc.text('Localidad / Ciudad', L + wNombreUE + 4 + (wLocalidad - 4) / 2, y, { align: 'center' })
  // ⬇️ etiqueta compacta para que no invada el valor escrito arriba
  doc.text('GRADO (1º–6º Secundaria)', L + wTotal - wGrado + 2, y, { align: 'left' })

  // Subtítulo LISTA DE ESTUDIANTES
  y += 8
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(9)
  doc.text('LISTA DE ESTUDIANTES', L, y)

  // Tabla estudiantes (mínimo 10 filas)
  y += 2
  const filas = Math.max(10, estudiantes.length)
  const body = []
  for (let i = 0; i < filas; i++) {
    const e = estudiantes[i] || {}
    body.push([
      (i + 1) + '.',
      e.apellidos || '',
      e.nombres || '',
      e.carnet || '',
    ])
  }

  autoTable(doc, {
    startY: y + 2,
    margin: { left: L, right: R },
    head: [['Nº', 'APELLIDOS', 'NOMBRES', 'CARNET']],
    body,
    theme: 'grid',
    styles: { fontSize: 9, cellPadding: 2 },
    headStyles: { fillColor: [0,0,0], textColor: [255,255,255], halign: 'center' },
    columnStyles: {
      0: { cellWidth: 12, halign: 'center' },
      1: { cellWidth: (W - L - R) * 0.38 },
      2: { cellWidth: (W - L - R) * 0.38 },
      3: { cellWidth: (W - L - R) * 0.20, halign: 'center' },
    }
  })

  y = doc.lastAutoTable.finalY + 8
  drawHLine(L, W - R, y) // línea larga

  // NOTA IMPORTANTE (bloque de texto)
  y += 6
  doc.setFont('helvetica', 'normal')
  doc.setFontSize(8.2)
  const nota =
    'NOTA IMPORTANTE: El Profesor(a) Tutor(a) se hace responsable de que todos los datos aquí proporcionados son ' +
    'fidedignos, tiene la autorización del Director de la Unidad Educativa y estos datos también HAN SIDO LLENADOS EN EL ' +
    'FORMULARIO DIGITAL correspondiente que se encuentra enlazado en la página web https://www.facebook.com/OliMatOr.'
  const notaW = W - L - R
  const notaLines = doc.splitTextToSize(nota, notaW)
  doc.text(notaLines, L, y)

  // Firmas
  y += (notaLines.length * 4) + 10
  // línea firma tutor
  drawHLine(L, L + (W - L - R) * 0.45, y)
  // línea sello dirección
  drawHLine(W - R - (W - L - R) * 0.45, W - R, y)

  y += 5
  doc.setFontSize(8)
  doc.text('FIRMA PROFESOR(A) TUTOR(A)', L + (W - L - R) * 0.225, y, { align: 'center' })
  doc.text('SELLO DIRECCIÓN UNIDAD EDUCATIVA', W - R - (W - L - R) * 0.225, y, { align: 'center' })

  // Lugar y Fecha
  y += 12
  doc.setFontSize(9)
  doc.text('Lugar y Fecha:', L, y)
  drawUnderlinedField(L + 24, y + 2, W - R - (L + 24))

  // Guardar
  doc.save('formulario_olim_matematica.pdf')
}

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
  // colegio
  if (inscrito?.colegio) {
    doc.text(`Colegio: ${inscrito.colegio}`, M + 40, y)
  }
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
  // if tutor
  if (inscrito?.tutor) {
    doc.text(`Tutor: ${inscrito.tutor}`, M + 65, innerY)
  }

  y += boxH + GAP

  // TABLA
  const body = (integrantes || []).map((i, idx) => [
    idx + 1,
    i.nombre || '',
    i.ci || '',
    i.curso || '',
    // i.tutor || '',
    i.telefono || ''
  ])

  autoTable(doc, {
    startY: y,
    margin: { top: TOP + 4, left: M, right: M, bottom: 28 },
    head: [['#', 'Nombre', 'CI', 'Curso', 'Teléfono']],
    body,
    theme: 'grid',
    styles: { fontSize: 9, cellPadding: 2, overflow: 'linebreak' },
    headStyles: { fillColor: PRIMARY, halign: 'center' },
    columnStyles: {
      0: { cellWidth: 8, halign: 'center' },    // #
      1: { cellWidth: 62 },                     // Nombre
      2: { cellWidth: 28 },                     // CI
      3: { cellWidth: 30 },                     // Curso
      // 4: { cellWidth: 32 },                     // Tutor
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
