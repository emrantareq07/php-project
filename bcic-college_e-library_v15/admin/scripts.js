window.jsPDF = window.jspdf.jsPDF;
const PRIMARY_COLOR = '#171f27';
const SECONDARY_COLOR = '#ffffff';
let doc = null;

// Improved image loading with retries and better error handling
async function getDataUri(url, dWidth, dHeight, retries = 2) {
  return new Promise((resolve, reject) => {
    const image = new Image();
    image.crossOrigin = 'anonymous';
    
    image.onload = function() {
      const canvas = document.createElement('canvas');
      canvas.width = dWidth;
      canvas.height = dHeight;

      const ctx = canvas.getContext('2d');
      
      // Draw white background for transparent images
      ctx.fillStyle = '#ffffff';
      ctx.fillRect(0, 0, dWidth, dHeight);
      
      // Calculate aspect ratio and positioning
      const imgAspect = this.naturalWidth / this.naturalHeight;
      const targetAspect = dWidth / dHeight;
      
      let drawWidth, drawHeight, offsetX, offsetY;
      
      if (imgAspect > targetAspect) {
        drawWidth = dWidth;
        drawHeight = dWidth / imgAspect;
        offsetX = 0;
        offsetY = (dHeight - drawHeight) / 2;
      } else {
        drawHeight = dHeight;
        drawWidth = dHeight * imgAspect;
        offsetY = 0;
        offsetX = (dWidth - drawWidth) / 2;
      }

      ctx.drawImage(this, offsetX, offsetY, drawWidth, drawHeight);

      // Create circular mask
      ctx.globalCompositeOperation = 'destination-in';
      ctx.beginPath();
      ctx.arc(dWidth * 0.5, dHeight * 0.5, dWidth * 0.5, 0, Math.PI * 2);
      ctx.fill();
      
      resolve(canvas.toDataURL('image/png'));
    };
    
    image.onerror = function() {
      if (retries > 0) {
        console.log(`Retrying image load for ${url}, ${retries} attempts left`);
        setTimeout(() => {
          getDataUri(url, dWidth, dHeight, retries - 1).then(resolve).catch(reject);
        }, 500);
      } else {
        console.error('Failed to load image:', url);
        const canvas = document.createElement('canvas');
        canvas.width = dWidth;
        canvas.height = dHeight;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#f0f0f0';
        ctx.fillRect(0, 0, dWidth, dHeight);
        ctx.fillStyle = '#999';
        ctx.font = '12px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Image not available', dWidth/2, dHeight/2);
        resolve(canvas.toDataURL('image/png'));
      }
    };
    
    image.src = url;
  });
}

function generateQrCodeUri(text) {
  try {
    const qrContainer = document.createElement('div');
    new QRCode(qrContainer, {
      text: text,
      width: 128,
      height: 128,
      colorDark: PRIMARY_COLOR,
      colorLight: SECONDARY_COLOR,
    });
    const canvas = qrContainer.querySelector('canvas');
    return canvas ? canvas.toDataURL('image/png') : '';
  } catch (error) {
    console.error('Error generating QR code:', error);
    return '';
  }
}

function downloadIdCard() {
  if (doc) {
    doc.save(`employee-id-cards-${new Date().toISOString().slice(0,10)}.pdf`);
  } else {
    alert('ID cards are still being generated. Please wait.');
  }
}

async function generateIdCard(data) {
  try {
    // Initialize PDF document
    doc = new jsPDF({
      orientation: 'portrait',
      unit: 'in',
      format: [2.125, 3.375], // Standard ID card size
    });

    // Add fonts
    doc.addFileToVFS('Poppins-Bold.ttf', POPPINS_BOLD);
    doc.addFont('Poppins-Bold.ttf', 'Poppins', 'bold');
    doc.addFileToVFS('Poppins-Medium.ttf', POPPINS_MEDIUM);
    doc.addFont('Poppins-Medium.ttf', 'Poppins', 'medium');

    // Process each employee
    for (const [index, employee] of data.entries()) {
      if (index > 0) {
        doc.addPage();
      }
    // Load your logo from the images folder
const logoUri = await getDataUri('student_images/Seal_of_BCIC_College.png', 400, 400);

    // Add company logo (adjust x, y, width, height as needed)
doc.addImage(logoUri, 'PNG', 0.2, 0.2, 0.4, 0.4); // Logo moved a little to the right


    // Company information (adjust x position to place next to the logo)
    doc.setFont('Poppins', 'bold');
    doc.setFontSize(12);
    doc.setTextColor(PRIMARY_COLOR);
    doc.text('BCIC College'.toUpperCase(), 0.65, 0.350, { align: 'left' }); // Text next to the logo

      
      doc.setFont('Poppins', 'medium');
doc.setFontSize(5.5);
doc.text('Zoo Road, Mirpur, Dhaka 1216.', 1.190, 0.450, { align: 'center' }); // Shifted slightly to the right


      // Background design
      doc.setFillColor(PRIMARY_COLOR);
      doc.triangle(0, 1.68, 2.125, 1.18, 2.125, 1.68, 'F');
      doc.rect(0, 1.68, 2.125, 1.697, 'F');

    // Library Card
      doc.setFont('Poppins', 'bold');
      doc.setFontSize(9);
      doc.setTextColor(PRIMARY_COLOR);
      doc.text('Library Card', 1.0625, .700, { align: 'center' });

      // Employee photo
      doc.setFillColor(SECONDARY_COLOR);
      doc.circle(1.063, 1.447, 0.1, 'F');
      
      const profileUri = await getDataUri(employee.image, 400, 400);
      doc.addImage(profileUri, 'PNG', 0.633, 1.090, 0.86, 0.86);

      

      // Employee details
      doc.setFont('Poppins', 'bold');
      doc.setFontSize(9);
      doc.setTextColor(SECONDARY_COLOR);
      doc.text(employee.name.toUpperCase(), 1.0625, 2.15, { align: 'center' });
      
      doc.setFont('Poppins', 'medium');
      doc.setFontSize(5.5);
      doc.text(employee.designation, 1.0625, 2.267, { align: 'center' });

      // Employee information fields
      doc.setFontSize(6);
      doc.setTextColor(SECONDARY_COLOR);

      
      
 const infoFields = [
  { label: 'Student ID', value: employee.id },
  { label: 'Session', value: employee.session },
  { label: 'Phone', value: employee.phone },
  { label: 'Valid Until', value: employee.session }
];

      
      infoFields.forEach((field, i) => {
        doc.text(field.label, 0.3, 2.583 + 0.167 * i);
        doc.text(':', 0.76, 2.583 + 0.167 * i);
        doc.text(field.value, 0.813, 2.583 + 0.167 * i);
      });

      // QR code
      doc.setFillColor(SECONDARY_COLOR);
      doc.rect(1.578, 2.846, 0.433, 0.433, 'F');
      
      const qrCodeUri = generateQrCodeUri(employee.id);
      if (qrCodeUri) {
        doc.addImage(qrCodeUri, 'PNG', 1.613, 2.882, 0.366, 0.366);
      }
    }

    // Update preview
    const pdfBlob = doc.output('blob');
    const pdfUrl = URL.createObjectURL(pdfBlob);
    const iframe = document.getElementById('idcard-preview');
    iframe.src = pdfUrl;
    
    // Clean up memory
    iframe.onload = () => {
      URL.revokeObjectURL(pdfUrl);
    };
    
    return true;
  } catch (error) {
    console.error('Error in generateIdCard:', error);
    document.getElementById('loading').textContent = 'Error generating ID cards. See console.';
    return false;
  }
}