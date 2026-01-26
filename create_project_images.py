from PIL import Image, ImageDraw, ImageFont
import os

# Brazilian flag colors
BRAZIL_GREEN = (0, 156, 59)
BRAZIL_YELLOW = (255, 223, 0)
BRAZIL_BLUE = (0, 39, 118)

projects = [
    ('brasil-api', 'BrasilAPI', '#667eea', '#764ba2', 'API de Dados Brasileiros'),
    ('querido-diario', 'Querido Diário', '#f093fb', '#f5576c', 'Plataforma Cívica'),
    ('login-cidadao', 'Login Cidadão', '#4facfe', '#00f2fe', 'Autenticação Governamental'),
    ('serenata-amor', 'Serenata de Amor', '#43e97b', '#38f9d7', 'IA para Controle Social'),
    ('plataforma-sabia', 'Plataforma Sabiá', '#fa709a', '#fee140', 'Tecnologias do Semiárido'),
    ('brasil-io', 'Brasil.IO', '#30cfd0', '#330867', 'Dados Públicos Acessíveis')
]

def hex_to_rgb(hex_color):
    hex_color = hex_color.lstrip('#')
    return tuple(int(hex_color[i:i+2], 16) for i in (0, 2, 4))

def create_gradient_image(width, height, color1, color2, title, subtitle):
    # Create image with gradient
    img = Image.new('RGB', (width, height), color1)
    draw = ImageDraw.Draw(img)
    
    # Create gradient effect
    for y in range(height):
        ratio = y / height
        r = int(color1[0] * (1 - ratio) + color2[0] * ratio)
        g = int(color1[1] * (1 - ratio) + color2[1] * ratio)
        b = int(color1[2] * (1 - ratio) + color2[2] * ratio)
        draw.line([(0, y), (width, y)], fill=(r, g, b))
    
    # Try to use a font, fallback to default if not available
    try:
        # Try to use a larger font
        title_font = ImageFont.truetype("arial.ttf", 60)
        subtitle_font = ImageFont.truetype("arial.ttf", 30)
    except:
        try:
            title_font = ImageFont.truetype("C:/Windows/Fonts/arial.ttf", 60)
            subtitle_font = ImageFont.truetype("C:/Windows/Fonts/arial.ttf", 30)
        except:
            title_font = ImageFont.load_default()
            subtitle_font = ImageFont.load_default()
    
    # Get text dimensions
    bbox = draw.textbbox((0, 0), title, font=title_font)
    title_width = bbox[2] - bbox[0]
    title_height = bbox[3] - bbox[1]
    
    # Draw title
    title_x = (width - title_width) // 2
    title_y = height // 2 - 40
    draw.text((title_x, title_y), title, fill=(255, 255, 255), font=title_font)
    
    # Draw subtitle
    bbox = draw.textbbox((0, 0), subtitle, font=subtitle_font)
    subtitle_width = bbox[2] - bbox[0]
    subtitle_x = (width - subtitle_width) // 2
    subtitle_y = title_y + title_height + 20
    draw.text((subtitle_x, subtitle_y), subtitle, fill=(255, 255, 255), font=subtitle_font)
    
    return img

# Create images directory if it doesn't exist
os.makedirs('images/projects', exist_ok=True)

# Generate images
for name, title, color1_hex, color2_hex, subtitle in projects:
    color1 = hex_to_rgb(color1_hex)
    color2 = hex_to_rgb(color2_hex)
    img = create_gradient_image(800, 600, color1, color2, title, subtitle)
    img.save(f'images/projects/{name}.jpg', 'JPEG', quality=90)
    print(f'Created {name}.jpg')

print('All project images created successfully!')
